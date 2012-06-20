<?php
namespace application\ehb_sync\bamaflex;

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
        $query = 'SELECT TOP 10 *  FROM [INFORDATSYNC].[dbo].[v_discovery_course_basic] WHERE programme_type in (1,4) AND exchange = 0 AND year = \'' . $this->get_academic_year() . '\'';
        return $this->get_result($query);
    }

    /**
     *
     * @return Group
     */
    function synchronize($course)
    {
        $current_course = WeblcmsDataManager :: get_instance()->retrieve_course_by_visual_code($course['id']);
        $name = $this->convert_to_utf8($course['name']);
        if (! $current_course instanceof Course)
        {
            $new_course = new Course();
            $new_course->set_visual_code($course['id']);
            $new_course->set_title($name);
            $category_code = 'TRA_' . $course['training_id'];
            if (! array_key_exists($category_code, $this->course_categories_cache))
            {
                $this->course_categories_cache[$category_code] = WeblcmsDataManager :: get_instance()->retrieve_course_category_by_code($category_code);
            }
            $new_course->set_category_id($this->course_categories_cache[$category_code]->get_id());
            $new_course->set_course_type_id(1);
            $new_course->create();
            
            self :: log('added', $new_course->get_title());
            flush();
        }
        else
        {
            if ($current_course->get_title() != $name)
            {
                $current_course->set_title($name);
                $current_course->update();
                
                self :: log('updated', $current_course->get_title());
                flush();
            }
        
        }
    }

}
?>