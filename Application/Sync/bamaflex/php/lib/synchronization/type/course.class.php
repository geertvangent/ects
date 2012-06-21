<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\EqualityCondition;
use application\weblcms\course\CourseDataManager;
use application\weblcms\CourseManagementRights;
use application\weblcms\CourseSettingsConnector;
use application\weblcms\CourseSettingsController;
use application\weblcms\CourseCategory;
use application\weblcms\course\Course;
use application\weblcms\WeblcmsDataManager;
use common\libraries\Utilities;

/**
 *
 * @package ehb.sync;
 */

class CourseSynchronization extends Synchronization
{
    private $course_categories_cache = array();
    
    const COURSE_TYPE = 1;

    function run()
    {
        $children = $this->get_children();
        
        while ($child = $children->next_result())
        {
            $this->synchronize($child);
        }
    }

    function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_course_basic] WHERE programme_type in (1,4) AND exchange = 0 AND year = \'' . $this->get_academic_year() . '\'';
        return $this->get_result($query);
    }

    /**
     *
     * @return Group
     */
    function synchronize($course)
    {
        $condition = new EqualityCondition(Course :: PROPERTY_VISUAL_CODE, $course['id']);
        $course_exists = CourseDataManager :: get_instance()->count_courses($condition);
        
        if ($course_exists == 0)
        {
            $new_course = new Course();
            $new_course->set_visual_code($course['id']);
            $new_course->set_title($this->convert_to_utf8($course['name']));
            $category_code = 'TRA_' . $course['training_id'];
            
            if (! array_key_exists($category_code, $this->course_categories_cache))
            {
                $this->course_categories_cache[$category_code] = WeblcmsDataManager :: get_instance()->retrieve_course_category_by_code($category_code);
            }
            $new_course->set_titular_id(null);
            $new_course->set_language('nl');
            $new_course->set_category_id($this->course_categories_cache[$category_code]->get_id());
            $new_course->set_course_type_id(self :: COURSE_TYPE);
            
            if ($new_course->create())
            {
                $setting_values = array();
                
                $setting_values[CourseSettingsController :: SETTING_PARAM_COURSE_SETTINGS] = array();
                $setting_values[CourseSettingsController :: SETTING_PARAM_COURSE_SETTINGS][CourseSettingsConnector :: CATEGORY] = $new_course->get_category_id();
                $setting_values[CourseSettingsController :: SETTING_PARAM_COURSE_SETTINGS][CourseSettingsConnector :: LANGUAGE] = $new_course->get_language();
                $setting_values[CourseSettingsController :: SETTING_PARAM_COURSE_SETTINGS][CourseSettingsConnector :: TITULAR] = $new_course->get_titular_id();
                
                $new_course->create_course_settings_from_values($setting_values);
                
                CourseManagementRights :: get_instance()->create_rights_from_values($new_course, array());
                self :: log('added', $new_course->get_title());
            }
            else
            {
                self :: log('failed', $new_course->get_title());
            }
            flush();
        }
    }
}
?>