<?php
namespace Ehb\Application\Atlantis\Role\Entitlement\Storage\DataClass;

use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Ehb\Application\Atlantis\Role\Entitlement\Storage\DataManager;

/**
 * application.atlantis.role.entitlement.
 * 
 * @author GillardMagali
 */
class Entitlement extends DataClass
{
    
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
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_RIGHT_ID;
        $extended_property_names[] = self::PROPERTY_ROLE_ID;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        return DataManager::getInstance();
    }

    /**
     * Returns the application_right_id of this Entitlement.
     * 
     * @return integer The application_right_id.
     */
    public function get_right_id()
    {
        return $this->get_default_property(self::PROPERTY_RIGHT_ID);
    }

    /**
     * Sets the application_right_id of this Entitlement.
     * 
     * @param $application_right_id integer
     */
    public function set_right_id($application_right_id)
    {
        $this->set_default_property(self::PROPERTY_RIGHT_ID, $application_right_id);
    }

    /**
     * Returns the role_id of this Entitlement.
     * 
     * @return integer The role_id.
     */
    public function get_role_id()
    {
        return $this->get_default_property(self::PROPERTY_ROLE_ID);
    }

    /**
     * Sets the role_id of this Entitlement.
     * 
     * @param $role_id integer
     */
    public function set_role_id($role_id)
    {
        $this->set_default_property(self::PROPERTY_ROLE_ID, $role_id);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return ClassnameUtilities::getInstance()->getClassnameFromNamespace(self::class_name(), true);
    }

    public function get_right()
    {
        if (! isset($this->right))
        {
            $this->right = \Ehb\Application\Atlantis\Application\Right\Storage\DataManager::retrieve_by_id(
                \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right::class_name(), 
                (int) $this->get_right_id());
        }
        return $this->right;
    }

    public function get_role()
    {
        if (! isset($this->role))
        {
            $this->role = \Ehb\Application\Atlantis\Role\Storage\DataManager::retrieve_by_id(
                \Ehb\Application\Atlantis\Role\Storage\DataClass\Role::class_name(), 
                (int) $this->get_role_id());
        }
        return $this->role;
    }
}
