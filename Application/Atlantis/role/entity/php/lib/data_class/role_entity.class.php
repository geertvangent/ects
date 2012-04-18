<?php
namespace application\atlantis\role\entity;

use group\GroupDataManager;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
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
    const PROPERTY_CONTEXT_ID = 'context_id';
    const PROPERTY_ROLE_ID = 'role_id';
    
    private $entity;
    private $role;
    private $context;

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

    function get_entity_type_image()
    {
        return Theme :: get_image('entity_type/' . $this->get_entity_type(), 'png', null, null, ToolbarItem :: DISPLAY_ICON, false, __NAMESPACE__);
    }

    function get_entity()
    {
        if (! isset($this->entity))
        {
            switch ($this->get_entity_type())
            {
                case 1 :
                    $this->entity = \user\DataManager :: retrieve(\user\User :: class_name(), (int) $this->get_entity_id());
                    break;
                case 2 :
                    $this->entity = GroupDataManager :: get_instance()->retrieve_group($this->get_entity_id());
                    break;
            }
        }
        return $this->entity;
    }

    function get_entity_name()
    {
        switch ($this->get_entity_type())
        {
            case 1 :
                return $this->get_entity()->get_fullname();
                break;
            case 2 :
                return $this->get_entity()->get_name();
                break;
        }
    }

    function get_role()
    {
        if (! isset($this->role))
        {
            $this->role = \application\atlantis\role\DataManager :: retrieve(\application\atlantis\role\Role :: class_name(), (int) $this->get_role_id());
        }
        return $this->role;
    }

    function get_context()
    {
        if (! isset($this->context))
        {
            $this->context = \application\atlantis\context\DataManager :: retrieve(\application\atlantis\context\Context :: class_name(), (int) $this->get_context_id());
        }
        return $this->context;
    }
}
