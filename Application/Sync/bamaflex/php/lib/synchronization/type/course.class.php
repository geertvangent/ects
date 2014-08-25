<?php
namespace application\ehb_sync\bamaflex;

use application\weblcms\course\CourseGroupRelation;
use application\weblcms\CourseManagementRights;
use application\weblcms\CourseSettingsConnector;
use application\weblcms\CourseSettingsController;
use application\weblcms\course\Course;
use core\group\Group;

/**
 *
 * @package ehb.sync;
 */
class CourseSynchronization extends Synchronization
{

    private $course_categories_cache = array();
    const COURSE_TYPE = array('2012-13' => 1, '2013-14' => 5, '2014-15' => 6);

    public function run()
    {
        $children = $this->get_children();

        while ($child = $children->next_result())
        {
            $this->synchronize($child);
        }
    }

    public function get_children()
    {
        $academic_years = explode(',', $this->get_academic_year());

        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_course_basic] WHERE programme_type in (1,4) AND exchange = 0 AND year IN (\'' .
             implode('\',\'', $academic_years) . '\')';
        return $this->get_result($query);
    }

    /**
     *
     * @return Group
     */
    public function synchronize($course)
    {
        $current_course = \application\weblcms\course\DataManager :: retrieve_course_by_visual_code($course['id']);

        if (! $current_course instanceof Course)
        {
            $new_course = new Course();
            $new_course->set_visual_code($course['id']);
            $new_course->set_title($this->convert_to_utf8($course['name']));
            $category_code = 'TRA_' . $course['training_id'];

            if (! array_key_exists($category_code, $this->course_categories_cache))
            {
                $this->course_categories_cache[$category_code] = \application\weblcms\DataManager :: retrieve_course_category_by_code(
                    $category_code);
            }
            $new_course->set_titular_id(null);
            $new_course->set_language('nl');
            $new_course->set_category_id($this->course_categories_cache[$category_code]->get_id());

            $course_types = self :: COURSE_TYPE;

            $new_course->set_course_type_id($course_types[$course['year']]);
            // $new_course->set_foreign_property(Course :: FOREIGN_PROPERTY_COURSE_TYPE, $value)

            if ($new_course->create())
            {
                $setting_values = array();

                $setting_values[CourseSettingsController :: SETTING_PARAM_COURSE_SETTINGS] = array();
                $setting_values[CourseSettingsController :: SETTING_PARAM_COURSE_SETTINGS][CourseSettingsConnector :: CATEGORY] = $new_course->get_category_id();
                $setting_values[CourseSettingsController :: SETTING_PARAM_COURSE_SETTINGS][CourseSettingsConnector :: LANGUAGE] = $new_course->get_language();
                $setting_values[CourseSettingsController :: SETTING_PARAM_COURSE_SETTINGS][CourseSettingsConnector :: TITULAR] = $new_course->get_titular_id();

                $new_course->create_course_settings_from_values($setting_values);

                CourseManagementRights :: get_instance()->create_rights_from_values($new_course, array());

                $teacher_code = 'COU_OP_' . $course['id'];
                $student_code = 'COU_STU_' . $course['id'];

                self :: log('added', $new_course->get_title());

                $group = \core\group\DataManager :: retrieve_group_by_code($teacher_code);
                if ($group instanceof Group)
                {
                    \application\weblcms\course\DataManager :: subscribe_group_to_course(
                        $new_course->get_id(),
                        $group->get_id(),
                        CourseGroupRelation :: STATUS_TEACHER);
                    self :: log('added teacher group', $new_course->get_title());
                }

                $group = \core\group\DataManager :: retrieve_group_by_code($student_code);
                if ($group instanceof Group)
                {
                    \application\weblcms\course\DataManager :: subscribe_group_to_course(
                        $new_course->get_id(),
                        $group->get_id(),
                        CourseGroupRelation :: STATUS_STUDENT);
                    self :: log('added student group', $new_course->get_title());
                }
            }
            else
            {
                self :: log('failed', $new_course->get_title());
            }
            flush();
        }
        else
        {
            $teacher_code = 'COU_OP_' . $course['id'];
            $student_code = 'COU_STU_' . $course['id'];

            $group = \core\group\DataManager :: retrieve_group_by_code($teacher_code);
            if ($group instanceof Group)
            {
                if (! \application\weblcms\course\DataManager :: is_group_direct_subscribed_to_course(
                    $current_course->get_id(),
                    $group->get_id()))
                {
                    \application\weblcms\course\DataManager :: subscribe_group_to_course(
                        $current_course->get_id(),
                        $group->get_id(),
                        CourseGroupRelation :: STATUS_TEACHER);
                    self :: log('added teacher group', $current_course->get_title());
                }
            }

            $group = \core\group\DataManager :: retrieve_group_by_code($student_code);
            if ($group instanceof Group)
            {
                if (! \application\weblcms\course\DataManager :: is_group_direct_subscribed_to_course(
                    $current_course->get_id(),
                    $group->get_id()))
                {
                    \application\weblcms\course\DataManager :: subscribe_group_to_course(
                        $current_course->get_id(),
                        $group->get_id(),
                        CourseGroupRelation :: STATUS_STUDENT);
                    self :: log('added student group', $current_course->get_title());
                }
            }
        }
    }
}
