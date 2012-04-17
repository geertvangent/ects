<?php
namespace application\atlantis\role\entity;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.atlantis.role.entity.
 * 
 * @author GillardMagali
 */
class RoleEntity extends DataClass
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * RoleEntity properties
     */
    const PROPERTY_ENTITY_TYPE = 'entity_type';
    const PROPERTY_ENTITY_ID = 'entity_id';
    const PROPERTY_CONTEXT_TYPE = 'context_type';
    const PROPERTY_CONTEXT_ID = 'context_id';
    const PROPERTY_ROLE_ID = 'role_id';

    /**
     * Get the default properties
     * 
     * @param $extended_property_names multitype:string           
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_ENTITY_TYPE;
        $extended_property_names[] = self :: PROPERTY_ENTITY_ID;
        $extended_property_names[] = self :: PROPERTY_CONTEXT_TYPE;
        $extended_property_names[] = self :: PROPERTY_CONTEXT_ID;
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
     * Returns the entity_type of this RoleEntity.
     * 
     * @return integer The entity_type.
     */
    function get_entity_type()
    {
        return $this->get_default_property(self :: PROPERTY_ENTITY_TYPE);
    }

    /**
     * Sets the entity_type of this RoleEntity.
     * 
     * @param $entity_type integer           
     */
    function set_entity_type($entity_type)
    {
        $this->set_default_property(self :: PROPERTY_ENTITY_TYPE, $entity_type);
    }

    /**
     * Returns the entity_id of this RoleEntity.
     * 
     * @return integer The entity_id.
     */
    function get_entity_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENTITY_ID);
    }

    /**
     * Sets the entity_id of this RoleEntity.
     * 
     * @param $entity_id integer           
     */
    function set_entity_id($entity_id)
    {
        $this->set_default_property(self :: PROPERTY_ENTITY_ID, $entity_id);
    }

    /**
     * Returns the context_type of this RoleEntity.
     * 
     * @return integer The context_type.
     */
    function get_context_type()
    {
        return $this->get_default_property(self :: PROPERTY_CONTEXT_TYPE);
    }

    /**
     * Sets the context_type of this RoleEntity.
     * 
     * @param $context_type integer           
     */
    function set_context_type($context_type)
    {
        $this->set_default_property(self :: PROPERTY_CONTEXT_TYPE, $context_type);
    }

    /**
     * Returns the context_id of this RoleEntity.
     * 
     * @return integer The context_id.
     */
    function get_context_id()
    {
        return $this->get_default_property(self :: PROPERTY_CONTEXT_ID);
    }

    /**
     * Sets the context_id of this RoleEntity.
     * 
     * @param $context_id integer           
     */
    function set_context_id($context_id)
    {
        $this->set_default_property(self :: PROPERTY_CONTEXT_ID, $context_id);
    }

    
    /**
     * Returns the role_id of this RoleEntity.
     *
     * @return integer The role_id.
     */
    function get_role_id()
    {
        return $this->get_default_property(self :: PROPERTY_ROLE_ID);
    }
    
    /**
     * Sets the role_id of this RoleEntity.
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
}
