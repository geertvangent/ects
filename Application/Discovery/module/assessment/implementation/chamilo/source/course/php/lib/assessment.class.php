<?php
namespace application\discovery\module\assessment\implementation\chamilo\source\course;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.assessment.implementation.chamilo.source.course.discovery
 * @author Hans De Bisschop
 */
class Assessment extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Assessment properties
     */
    const PROPERTY_COURSE = 'course';

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_COURSE;

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
     * Returns the course of this Assessment.
     * @return string The course.
     */
    function get_course()
    {
        return $this->get_default_property(self :: PROPERTY_COURSE);
    }

    /**
     * Sets the course of this Assessment.
     * @param string $course
     */
    function set_course($course)
    {
        $this->set_default_property(self :: PROPERTY_COURSE, $course);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
