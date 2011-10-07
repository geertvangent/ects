<?php
namespace application\discovery\module\course_results;

use application\discovery\DiscoveryDataManager;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.course_results.discovery
 * @author Hans De Bisschop
 */
class MarkMoment extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Mark moment properties
     */
    const PROPERTY_NAME = 'name';

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_NAME;

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
     * Returns the name of this MarkMoment.
     * @return string The name.
     */
    function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    /**
     * Sets the name of this Mark.
     * @param string $name
     */
    function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
