<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type;

use Ehb\Application\Sync\Bamaflex\Synchronization\Synchronization;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Application\Weblcms\Course\Storage\DataClass\Course;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Application\Weblcms\Admin\Extension\Platform\Storage\DataClass\Admin;
use Chamilo\Application\Weblcms\Admin\Extension\Platform\Entity\UserEntity;
use Chamilo\Application\Weblcms\Admin\Extension\Platform\Entity\CourseEntity;

/**
 *
 * @package ehb.sync;
 */
class AdminSynchronization extends Synchronization
{
    const PROPERTY_PERSON_ID = 'person_id';
    const PROPERTY_COURSE_ID = 'course_id';

    public function run()
    {
        // Get the list of people who are defined as an administrator in BaMaFlex for the given academic year
        $query = 'SELECT DISTINCT person_id FROM [INFORDATSYNC].[dbo].[v_desiderius_sub_manager] WHERE person_id IS NOT NULL AND year >= \'2012-13\'';
        $results = $this->get_result($query);

        $person_ids = array();

        while ($record = $results->next_result())
        {
            $person_ids[] = $record[self :: PROPERTY_PERSON_ID];
        }

        // Get the Desiderius user id's for these person ids
        $condition = new InCondition(
            new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_OFFICIAL_CODE),
            $person_ids);
        $bamaflex_user_ids = \Chamilo\Core\User\Storage\DataManager :: distinct(
            User :: class_name(),
            new DataClassDistinctParameters($condition, User :: PROPERTY_ID));

        // Get the people currently linked to those courses
        $conditions = array();
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ORIGIN),
            new StaticConditionVariable(Admin :: ORIGIN_EXTERNAL));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ENTITY_TYPE),
            new StaticConditionVariable(UserEntity :: ENTITY_TYPE));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_TARGET_TYPE),
            new StaticConditionVariable(CourseEntity :: ENTITY_TYPE));
        $condition = new AndCondition($conditions);

        $platform_user_ids = \Chamilo\Application\Weblcms\Admin\Extension\Platform\Storage\DataManager :: distinct(
            Admin :: class_name(),
            new DataClassDistinctParameters($condition, Admin :: PROPERTY_ENTITY_ID));

        $new_users = array_diff($bamaflex_user_ids, $platform_user_ids);

        if (count($new_users) > 0)
        {
            $this->process_new_users($new_users);
        }

        $old_users = array_diff($platform_user_ids, $bamaflex_user_ids);

        if (count($old_users) > 0)
        {
            $this->process_old_users($old_users);
        }

        $existing_users = array_intersect($platform_user_ids, $bamaflex_user_ids);

        if (count($existing_users) > 0)
        {
            $this->process_existing_users($existing_users);
        }
    }

    private function process_new_users($new_user_ids)
    {
        foreach ($new_user_ids as $new_user_id)
        {
            $user = \Chamilo\Core\User\Storage\DataManager :: retrieve_by_id(User :: class_name(), $new_user_id);
            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_desiderius_sub_manager] WHERE person_id = ' .
                 $user->get_official_code() . ' AND year >= \'2012-13\'';
            $results = $this->get_result($query);

            while ($result = $results->next_result())
            {
                $course = \Chamilo\Application\Weblcms\Course\Storage\DataManager :: retrieve(
                    Course :: class_name(),
                    new DataClassRetrieveParameters(
                        new EqualityCondition(
                            new PropertyConditionVariable(Course :: class_name(), Course :: PROPERTY_VISUAL_CODE),
                            new StaticConditionVariable($result[self :: PROPERTY_COURSE_ID]))));

                if ($course instanceof Course)
                {
                    $admin = new Admin();
                    $admin->set_origin(Admin :: ORIGIN_EXTERNAL);
                    $admin->set_entity_type(UserEntity :: ENTITY_TYPE);
                    $admin->set_entity_id($new_user_id);
                    $admin->set_target_type(CourseEntity :: ENTITY_TYPE);
                    $admin->set_target_id($course->get_id());

                    if (! $admin->create())
                    {
                        self :: log('Adding failed', 'User ' . $new_user_id . ' to course ' . $course->get_id());
                        flush();
                    }
                    else
                    {
                        self :: log('Adding', 'User ' . $new_user_id . ' to course ' . $course->get_id());
                        flush();
                    }
                }
                else
                {
                    self :: log(
                        'Adding failed',
                        'User ' . $new_user_id . ' to course ' . $result[self :: PROPERTY_COURSE_ID] .
                             ' (no such course)');
                    flush();
                }
            }
        }
    }

    private function process_old_users($old_user_ids)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ORIGIN),
            new StaticConditionVariable(Admin :: ORIGIN_EXTERNAL));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ENTITY_TYPE),
            new StaticConditionVariable(UserEntity :: ENTITY_TYPE));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_TARGET_TYPE),
            new StaticConditionVariable(CourseEntity :: ENTITY_TYPE));
        $conditions[] = new InCondition(
            new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ENTITY_ID),
            $old_user_ids);
        $condition = new AndCondition($conditions);

        if (! \Chamilo\Application\Weblcms\Admin\Extension\Platform\Storage\DataManager :: deletes(
            Admin :: class_name(),
            $condition))
        {
            self :: log('Removing failed', 'Users: ' . implode(', ', $old_user_ids));
            flush();
        }
        else
        {
            self :: log('Removed', 'Users: ' . implode(', ', $old_user_ids));
            flush();
        }
    }

    private function process_existing_users($existing_user_ids)
    {
        foreach ($existing_user_ids as $existing_user_id)
        {
            $user = \Chamilo\Core\User\Storage\DataManager :: retrieve_by_id(User :: class_name(), $existing_user_id);
            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_desiderius_sub_manager] WHERE person_id = ' .
                 $user->get_official_code() . ' AND year >= \'2012-13\'';
            $results = $this->get_result($query);

            $bamaflex_course_ids = array();

            while ($record = $results->next_result())
            {
                $course = \Chamilo\Application\Weblcms\Course\Storage\DataManager :: retrieve(
                    Course :: class_name(),
                    new DataClassRetrieveParameters(
                        new EqualityCondition(
                            new PropertyConditionVariable(Course :: class_name(), Course :: PROPERTY_VISUAL_CODE),
                            new StaticConditionVariable($record[self :: PROPERTY_COURSE_ID]))));

                if ($course instanceof Course)
                {
                    $bamaflex_course_ids[] = $course->get_id();
                }
            }

            $conditions = array();
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ORIGIN),
                new StaticConditionVariable(Admin :: ORIGIN_EXTERNAL));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ENTITY_TYPE),
                new StaticConditionVariable(UserEntity :: ENTITY_TYPE));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_TARGET_TYPE),
                new StaticConditionVariable(CourseEntity :: ENTITY_TYPE));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ENTITY_ID),
                new StaticConditionVariable($existing_user_id));
            $condition = new AndCondition($conditions);

            $platform_course_ids = \Chamilo\Application\Weblcms\Admin\Extension\Platform\Storage\DataManager :: distinct(
                Admin :: class_name(),
                new DataClassDistinctParameters($condition, Admin :: PROPERTY_TARGET_ID));

            $new_course_ids = array_diff($bamaflex_course_ids, $platform_course_ids);

            foreach ($new_course_ids as $new_course_id)
            {
                $admin = new Admin();
                $admin->set_origin(Admin :: ORIGIN_EXTERNAL);
                $admin->set_entity_type(UserEntity :: ENTITY_TYPE);
                $admin->set_entity_id($existing_user_id);
                $admin->set_target_type(CourseEntity :: ENTITY_TYPE);
                $admin->set_target_id($new_course_id);

                if (! $admin->create())
                {
                    self :: log('Adding failed', 'User ' . $existing_user_id . ' to course ' . $new_course_id);
                    flush();
                }
                else
                {
                    self :: log('Adding', 'User ' . $existing_user_id . ' to course ' . $new_course_id);
                    flush();
                }
            }

            $old_course_ids = array_diff($platform_course_ids, $bamaflex_course_ids);

            if (count($old_course_ids) > 0)
            {
                $conditions = array();
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ORIGIN),
                    new StaticConditionVariable(Admin :: ORIGIN_EXTERNAL));
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ENTITY_TYPE),
                    new StaticConditionVariable(UserEntity :: ENTITY_TYPE));
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_TARGET_TYPE),
                    new StaticConditionVariable(CourseEntity :: ENTITY_TYPE));
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_ENTITY_ID),
                    new StaticConditionVariable($existing_user_id));
                $conditions[] = new InCondition(
                    new PropertyConditionVariable(Admin :: class_name(), Admin :: PROPERTY_TARGET_ID),
                    $old_course_ids);
                $condition = new AndCondition($conditions);

                if (! \Chamilo\Application\Weblcms\Admin\Extension\Platform\Storage\DataManager :: deletes(
                    Admin :: class_name(),
                    $condition))
                {
                    self :: log(
                        'Removing failed',
                        'User ' . $existing_user_id . ' with courses: ' . implode(', ', $old_course_ids));
                    flush();
                }
                else
                {
                    self :: log(
                        'Removed',
                        'User ' . $existing_user_id . ' with courses: ' . implode(', ', $old_course_ids));
                    flush();
                }
            }
        }
    }
}
