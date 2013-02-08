<?php
namespace application\discovery\module\course_results;

use application\discovery\DiscoveryDataManager;
use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.course_results.discovery
 *
 * @author Hans De Bisschop
 */
class CourseResult extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Course properties
     */
    const PROPERTY_MARKS = 'marks';
    const PROPERTY_PERSON_FIRST_NAME = 'person_first_name';
    const PROPERTY_PERSON_LAST_NAME = 'person_last_name';
    const PROPERTY_PERSON_ID = 'person_id';

    /**
     * Get the default properties
     *
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_MARKS;
        $extended_property_names[] = self :: PROPERTY_PERSON_FIRST_NAME;
        $extended_property_names[] = self :: PROPERTY_PERSON_LAST_NAME;
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     *
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * Returns the marks of this Course.
     *
     * @return multitype:Mark The marks.
     */
    function get_marks()
    {
        return $this->get_default_property(self :: PROPERTY_MARKS);
    }

    /**
     * Returns the person_first_name of this CourseResults.
     *
     * @return string
     */
    function get_person_first_name()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_FIRST_NAME);
    }

    /**
     * Returns the person_last_name of this CourseResults.
     *
     * @return string
     */
    function get_person_last_name()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_LAST_NAME);
    }

    function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    function get_mark_by_moment_id($moment_id)
    {
        foreach ($this->get_marks() as $mark)
        {
            if ($mark->get_moment() == $moment_id)
            {
                return $mark;
            }
        }

        return self :: factory($moment_id);
    }

    /**
     * Sets the marks of this Course.
     *
     * @param multitype:Mark $marks
     */
    function set_marks($marks)
    {
        $this->set_default_property(self :: PROPERTY_MARKS, $marks);
    }

    /**
     * Sets the person_last_name of this CourseResults.
     *
     * @param string
     */
    function set_person_last_name($person_last_name)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_LAST_NAME, $person_last_name);
    }

    /**
     * Sets the person_first_name of this CourseResults.
     *
     * @param string
     */
    function set_person_first_name($person_first_name)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_FIRST_NAME, $person_first_name);
    }

    function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    /**
     *
     * @param Mark $mark
     */
    function add_mark(Mark $mark)
    {
        $marks = $this->get_marks();
        $marks[] = $mark;
        $this->set_marks($marks);
    }

    /**
     *
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
