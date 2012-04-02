<?php
namespace application\atlantis\application;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.atlantis.application.
 * @author GillardMagali
 */
class ApplicationRight extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * ApplicationRight properties
     */
    const PROPERTY_APPLICATION_ID = 'application_id';
    const PROPERTY_RIGHT_ID = 'right_id';

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_APPLICATION_ID;
        $extended_property_names[] = self :: PROPERTY_RIGHT_ID;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * @return DataManagerInterface
     */
    function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    /**
     * Returns the application_id of this ApplicationRight.
     * @return integer The application_id.
     */
    function get_application_id()
    {
        return $this->get_default_property(self :: PROPERTY_APPLICATION_ID);
    }

    /**
     * Sets the application_id of this ApplicationRight.
     * @param integer $application_id
     */
    function set_application_id($application_id)
    {
        $this->set_default_property(self :: PROPERTY_APPLICATION_ID, $application_id);
    }
    /**
     * Returns the right_id of this ApplicationRight.
     * @return integer The right_id.
     */
    function get_right_id()
    {
        return $this->get_default_property(self :: PROPERTY_RIGHT_ID);
    }

    /**
     * Sets the right_id of this ApplicationRight.
     * @param integer $right_id
     */
    function set_right_id($right_id)
    {
        $this->set_default_property(self :: PROPERTY_RIGHT_ID, $right_id);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
