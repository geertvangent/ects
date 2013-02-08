<?php
namespace application\atlantis\role\entitlement;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.atlantis.role.entitlement.
 *
 * @author GillardMagali
 */
class Entitlement extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Entitlement properties
     */
    const PROPERTY_RIGHT_ID = 'right_id';
    const PROPERTY_ROLE_ID = 'role_id';
    private $right;
    private $role;

    /**
     * Get the default properties
     *
     * @param $extended_property_names multitype:string
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_RIGHT_ID;
        $extended_property_names[] = self :: PROPERTY_ROLE_ID;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     *
     * @return DataManagerInterface
     */
    function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    /**
     * Returns the application_right_id of this Entitlement.
     *
     * @return integer The application_right_id.
     */
    function get_right_id()
    {
        return $this->get_default_property(self :: PROPERTY_RIGHT_ID);
    }

    /**
     * Sets the application_right_id of this Entitlement.
     *
     * @param $application_right_id integer
     */
    function set_right_id($application_right_id)
    {
        $this->set_default_property(self :: PROPERTY_RIGHT_ID, $application_right_id);
    }

    /**
     * Returns the role_id of this Entitlement.
     *
     * @return integer The role_id.
     */
    function get_role_id()
    {
        return $this->get_default_property(self :: PROPERTY_ROLE_ID);
    }

    /**
     * Sets the role_id of this Entitlement.
     *
     * @param $role_id integer
     */
    function set_role_id($role_id)
    {
        $this->set_default_property(self :: PROPERTY_ROLE_ID, $role_id);
    }

    /**
     *
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    function get_right()
    {
        if (! isset($this->right))
        {
            $this->right = \application\atlantis\application\right\DataManager :: retrieve(\application\atlantis\application\right\Right :: class_name(), (int) $this->get_right_id());
        }
        return $this->right;
    }

    function get_role()
    {
        if (! isset($this->role))
        {
            $this->role = \application\atlantis\role\DataManager :: retrieve(\application\atlantis\role\Role :: class_name(), (int) $this->get_role_id());
        }
        return $this->role;
    }
}
