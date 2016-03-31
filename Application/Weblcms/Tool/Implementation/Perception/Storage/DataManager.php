<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception\Storage;

use Chamilo\Application\Weblcms\Storage\DataClass\CourseEntityRelation;
use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Join;
use Chamilo\Libraries\Storage\Query\Joins;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\String\Text;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Storage\DataClass\Password;

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
        $userConditions = array();
        $userConditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                CourseEntityRelation :: class_name(),
                CourseEntityRelation :: PROPERTY_COURSE_ID),
            new StaticConditionVariable($course_id));
        $userConditions[] = new EqualityCondition(
            new PropertyConditionVariable(CourseEntityRelation :: class_name(), CourseEntityRelation :: PROPERTY_STATUS),
            new StaticConditionVariable(CourseEntityRelation :: STATUS_STUDENT));
        $userConditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                CourseEntityRelation :: class_name(),
                CourseEntityRelation :: PROPERTY_ENTITY_TYPE),
            new StaticConditionVariable(CourseEntityRelation :: ENTITY_TYPE_USER));

        $parameters = new DataClassDistinctParameters(
            new AndCondition($userConditions),
            CourseEntityRelation :: PROPERTY_ENTITY_ID);

        $user_ids = self :: distinct(
            \Chamilo\Application\Weblcms\Storage\DataClass\CourseEntityRelation :: class_name(),
            $parameters);

        // Retrieve the users subscribed through platform groups
        $groupConditions = array();
        $groupConditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                CourseEntityRelation :: class_name(),
                CourseEntityRelation :: PROPERTY_COURSE_ID),
            new StaticConditionVariable($course_id));
        $groupConditions[] = new EqualityCondition(
            new PropertyConditionVariable(CourseEntityRelation :: class_name(), CourseEntityRelation :: PROPERTY_STATUS),
            new StaticConditionVariable(CourseEntityRelation :: STATUS_STUDENT));
        $groupConditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                CourseEntityRelation :: class_name(),
                CourseEntityRelation :: PROPERTY_ENTITY_TYPE),
            new StaticConditionVariable(CourseEntityRelation :: ENTITY_TYPE_GROUP));

        $groups = \Chamilo\Application\Weblcms\Course\Storage\DataManager :: retrieves(
            Group :: class_name(),
            new DataClassRetrievesParameters(
                new AndCondition($groupConditions),
                null,
                null,
                array(),
                new Joins(
                    new Join(
                        CourseEntityRelation :: class_name(),
                        new EqualityCondition(
                            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_ID),
                            new PropertyConditionVariable(
                                CourseEntityRelation :: class_name(),
                                CourseEntityRelation :: PROPERTY_ENTITY_ID))))));

        $parameters = new DataClassDistinctParameters(
            new AndCondition($userConditions),
            CourseEntityRelation :: PROPERTY_ENTITY_ID);

        $groupIdentifiers = self :: distinct(
            \Chamilo\Application\Weblcms\Storage\DataClass\CourseEntityRelation :: class_name(),
            $parameters);

        $group_users = array();

        while ($group = $groups->next_result())
        {
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

        $users = \Chamilo\Core\User\Storage\DataManager :: retrieves(
            User :: class_name(),
            new DataClassRetrievesParameters($condition));

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
