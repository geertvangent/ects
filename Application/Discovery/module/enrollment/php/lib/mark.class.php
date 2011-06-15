<?php
namespace application\discovery\module\enrollment;

use application\discovery\DiscoveryDataManager;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.career.discovery
 * @author Hans De Bisschop
 */
class Mark extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Mark properties
     */
    const PROPERTY_MOMENT = 'moment';
    const PROPERTY_RESULT = 'result';
    const PROPERTY_STATUS = 'status';

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_MOMENT;
        $extended_property_names[] = self :: PROPERTY_RESULT;
        $extended_property_names[] = self :: PROPERTY_STATUS;

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
     * Returns the moment of this Mark.
     * @return string The moment.
     */
    function get_moment()
    {
        return $this->get_default_property(self :: PROPERTY_MOMENT);
    }

    /**
     * Sets the moment of this Mark.
     * @param string $moment
     */
    function set_moment($moment)
    {
        $this->set_default_property(self :: PROPERTY_MOMENT, $moment);
    }

    /**
     * Returns the result of this Mark.
     * @return string The result.
     */
    function get_result()
    {
        return $this->get_default_property(self :: PROPERTY_RESULT);
    }

    /**
     * Sets the result of this Mark.
     * @param string $result
     */
    function set_result($result)
    {
        $this->set_default_property(self :: PROPERTY_RESULT, $result);
    }

    /**
     * Returns the status of this Mark.
     * @return string The status.
     */
    function get_status()
    {
        return $this->get_default_property(self :: PROPERTY_STATUS);
    }

    /**
     * Sets the status of this Mark.
     * @param string $status
     */
    function set_status($status)
    {
        $this->set_default_property(self :: PROPERTY_STATUS, $status);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
