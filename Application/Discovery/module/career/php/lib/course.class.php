<?php
namespace application\discovery\module\career;

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

    /**
     * Sets the marks of this Course.
     * @param multitype:Mark $marks
     */
    function set_marks($marks)
    {
        $this->set_default_property(self :: PROPERTY_MARKS, $marks);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
