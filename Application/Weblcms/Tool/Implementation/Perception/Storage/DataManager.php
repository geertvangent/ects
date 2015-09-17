<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception;

use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Application\Weblcms\Course\Storage\DataClass\CourseUserRelation;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Application\Weblcms\Course\Storage\DataClass\CourseGroupRelation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Utilities\String\Text;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Perception
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
{
    const PREFIX = 'weblcms_perception_';

    const GENERATION_ATTEMPTS = 0;
    const GENERATION_FAILURES = 1;

    /**
     *
     * @param integer $course_id
     * @return integer[]
     */
    public static function get_course_user_ids($course_id)
    {
        // Retrieve the users directly subscribed to the course
        $relation_conditions = array();
        $relation_conditions[] = new EqualityCondition(
            new PropertyConditionVariable(CourseUserRelation :: class_name(), CourseUserRelation :: PROPERTY_COURSE_ID),
            new StaticConditionVariable($course_id));
        $relation_conditions[] = new EqualityCondition(
            new PropertyConditionVariable(CourseUserRelation :: class_name(), CourseUserRelation :: PROPERTY_STATUS),
            new StaticConditionVariable(CourseUserRelation :: STATUS_STUDENT));
        $course_user_relation_result_set = \Chamilo\Application\Weblcms\Course\Storage\Datamanager :: retrieves(
            CourseUserRelation :: class_name(),
            new DataClassRetrievesParameters(new AndCondition($relation_conditions)));

        $user_ids = array();
        while ($course_user = $course_user_relation_result_set->next_result())
        {
            $user_ids[] = $course_user->get_user_id();
        }

        // Retrieve the users subscribed through platform groups

        $relation_conditions = array();
        $relation_conditions[] = new EqualityCondition(
            new PropertyConditionVariable(CourseGroupRelation :: class_name(), CourseGroupRelation :: PROPERTY_COURSE_ID),
            new StaticConditionVariable($course_id));
        $relation_conditions[] = new EqualityCondition(
            new PropertyConditionVariable(CourseGroupRelation :: class_name(), CourseGroupRelation :: PROPERTY_STATUS),
            new StaticConditionVariable(CourseGroupRelation :: STATUS_STUDENT));
        $course_group_relations = \Chamilo\Application\Weblcms\Course\Storage\Datamanager :: retrieves(
            CourseGroupRelation :: class_name(),
            new DataClassRetrievesParameters(new AndCondition($relation_conditions)));

        $group_users = array();

        while ($group_relation = $course_group_relations->next_result())
        {
            $group = $group_relation->get_group();
            $group_user_ids = $group->get_users(true, true);

            $group_users = array_merge($group_users, $group_user_ids);
        }

        return array_unique(array_merge($user_ids, $group_users));
    }

    /**
     *
     * @param integer $course_id
     * @return string[]
     */
    public static function generate_passwords($course_id)
    {
        $users_ids = self :: get_course_user_ids($course_id);
        $password_user_ids = DataManager :: distinct(Password :: class_name(), PassWord :: PROPERTY_USER_ID);

        $conditions = array();
        $conditions[] = new InCondition(
            new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_ID),
            $users_ids);
        $conditions[] = new NotCondition(
            new InCondition(new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_ID), $password_user_ids));
        $condition = new AndCondition($conditions);

        $users = \Chamilo\Core\User\Storage\DataManager :: retrieves(User :: class_name(), $condition);

        $failures = 0;

        while ($user = $users->next_result())
        {
            $password = new Password();
            $password->set_user_id($user->get_id());
            $password->set_password(Text :: generate_password());
            if (! $password->create())
            {
                $failures ++;
            }
        }

        return array(self :: GENERATION_ATTEMPTS => $users->size(), self :: GENERATION_FAILURES => $failures);
    }
}
