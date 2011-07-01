<?php
namespace application\discovery\module\career;

use application\discovery\DiscoveryDataManager;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.career.discovery
 * @author Hans De Bisschop
 */
class Course extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Course properties
     */
    const PROPERTY_YEAR = 'year';
    const PROPERTY_NAME = 'name';
    const PROPERTY_MARKS = 'marks';
    const PROPERTY_CHILDREN = 'children';

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_MARKS;
        $extended_property_names[] = self :: PROPERTY_CHILDREN;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * Returns the year of this Course.
     * @return string The year.
     */
    function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     * Sets the year of this Course.
     * @param string $year
     */
    function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     * Returns the name of this Course.
     * @return string The name.
     */
    function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    /**
     * Sets the name of this Course.
     * @param string $name
     */
    function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    /**
     * Returns the marks of this Course.
     * @return multitype:Mark The marks.
     */
    function get_marks()
    {
        return $this->get_default_property(self :: PROPERTY_MARKS);
    }

    function get_mark_by_moment_id($moment_id)
    {
//        if ($this->has_children())
//        {
//            $total_credits = 0;
//            $total_results = 0;
//
//            foreach ($this->get_children() as $child)
//            {
//                foreach ($child->get_marks() as $mark)
//                {
//                    if ($mark->get_moment() == $moment_id && !is_null($mark->get_result()))
//                    {
//                        $total_credits += $child->get_credits();
//                        $total_results += ($mark->get_result() * $child->get_credits());
//                        break;
//                    }
//                }
//            }
//
//            return Mark :: factory($moment_id, ($total_results / $total_credits), 'McDuck');
//        }

        foreach ($this->get_marks() as $mark)
        {
            if ($mark->get_moment() == $moment_id)
            {
                return $mark;
            }
        }

        return Mark :: factory($moment_id);
    }

    /**
     * Sets the marks of this Course.
     * @param multitype:Mark $marks
     */
    function set_marks($marks)
    {
        $this->set_default_property(self :: PROPERTY_MARKS, $marks);
    }

    /**
     * Returns the children of this Course.
     * @return multitype:Course The children.
     */
    function get_children()
    {
        return $this->get_default_property(self :: PROPERTY_CHILDREN);
    }

    /**
     * Sets the children of this Course.
     * @param multitype:Course $children
     */
    function set_children($children)
    {
        $this->set_default_property(self :: PROPERTY_CHILDREN, $children);
    }

    /**
     * @param Course $course
     */
    function add_child(Course $course)
    {
        $courses = $this->get_children();
        $courses[] = $course;
        $this->set_children($courses);
    }

    /**
     * @param Mark $mark
     */
    function add_mark(Mark $mark)
    {
        $marks = $this->get_marks();
        $marks[] = $mark;
        $this->set_marks($marks);
    }

    /**
     * @return boolean
     */
    function has_children()
    {
        return count($this->get_children()) > 0;
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
