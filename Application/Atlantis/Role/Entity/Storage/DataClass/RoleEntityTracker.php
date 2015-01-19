<?php
namespace Ehb\Application\Atlantis\Role\Entity\Storage\DataClass;

use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Application\Atlantis\Role\Entity\Storage\DataManager;

/**
 * application.atlantis.role.entity.
 *
 * @author GillardMagali
 */
class RoleEntityTracker extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * RoleEntity properties
     */
    const PROPERTY_ENTITY_TYPE = 'entity_type';
    const PROPERTY_ENTITY_ID = 'entity_id';
    const PROPERTY_CONTEXT_ID = 'context_id';
    const PROPERTY_ROLE_ID = 'role_id';
    const PROPERTY_START_DATE = 'start_date';
    const PROPERTY_END_DATE = 'end_date';
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_ACTION_DATE = 'action_date';
    const PROPERTY_ACTION_TYPE = 'action_type';
    const ACTION_TYPE_CREATE = 1;
    const ACTION_TYPE_DELETE = 2;
    const ACTION_TYPE_MERGE = 3;

    private $entity;

    private $role;

    private $context;

    /**
     * Get the default properties
     *
     * @param $extended_property_names multitype:string
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_ENTITY_TYPE;
        $extended_property_names[] = self :: PROPERTY_ENTITY_ID;
        $extended_property_names[] = self :: PROPERTY_CONTEXT_ID;
        $extended_property_names[] = self :: PROPERTY_ROLE_ID;
        $extended_property_names[] = self :: PROPERTY_START_DATE;
        $extended_property_names[] = self :: PROPERTY_END_DATE;
        $extended_property_names[] = self :: PROPERTY_USER_ID;
        $extended_property_names[] = self :: PROPERTY_ACTION_DATE;
        $extended_property_names[] = self :: PROPERTY_ACTION_TYPE;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    /**
     * Returns the entity_type of this RoleEntity.
     *
     * @return integer The entity_type.
     */
    public function get_entity_type()
    {
        return $this->get_default_property(self :: PROPERTY_ENTITY_TYPE);
    }

    /**
     * Sets the entity_type of this RoleEntity.
     *
     * @param $entity_type integer
     */
    public function set_entity_type($entity_type)
    {
        $this->set_default_property(self :: PROPERTY_ENTITY_TYPE, $entity_type);
    }

    /**
     * Returns the entity_id of this RoleEntity.
     *
     * @return integer The entity_id.
     */
    public function get_entity_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENTITY_ID);
    }

    /**
     * Sets the entity_id of this RoleEntity.
     *
     * @param $entity_id integer
     */
    public function set_entity_id($entity_id)
    {
        $this->set_default_property(self :: PROPERTY_ENTITY_ID, $entity_id);
    }

    /**
     * Returns the context_id of this RoleEntity.
     *
     * @return integer The context_id.
     */
    public function get_context_id()
    {
        return $this->get_default_property(self :: PROPERTY_CONTEXT_ID);
    }

    /**
     * Sets the context_id of this RoleEntity.
     *
     * @param $context_id integer
     */
    public function set_context_id($context_id)
    {
        $this->set_default_property(self :: PROPERTY_CONTEXT_ID, $context_id);
    }

    /**
     * Returns the role_id of this RoleEntity.
     *
     * @return integer The role_id.
     */
    public function get_role_id()
    {
        return $this->get_default_property(self :: PROPERTY_ROLE_ID);
    }

    /**
     * Sets the role_id of this RoleEntity.
     *
     * @param $role_id integer
     */
    public function set_role_id($role_id)
    {
        $this->set_default_property(self :: PROPERTY_ROLE_ID, $role_id);
    }

    public function get_start_date()
    {
        return $this->get_default_property(self :: PROPERTY_START_DATE);
    }

    public function set_start_date($start_date)
    {
        $this->set_default_property(self :: PROPERTY_START_DATE, $start_date);
    }

    public function get_end_date()
    {
        return $this->get_default_property(self :: PROPERTY_END_DATE);
    }

    public function set_end_date($end_date)
    {
        $this->set_default_property(self :: PROPERTY_END_DATE, $end_date);
    }

    public function get_user_id()
    {
        return $this->get_default_property(self :: PROPERTY_USER_ID);
    }

    public function set_user_id($user_id)
    {
        $this->set_default_property(self :: PROPERTY_USER_ID, $user_id);
    }

    public function get_action_date()
    {
        return $this->get_default_property(self :: PROPERTY_ACTION_DATE);
    }

    public function set_action_date($action_date)
    {
        $this->set_default_property(self :: PROPERTY_ACTION_DATE, $action_date);
    }

    public function get_action_type()
    {
        return $this->get_default_property(self :: PROPERTY_ACTION_TYPE);
    }

    public function set_action_type($action_type)
    {
        $this->set_default_property(self :: PROPERTY_ACTION_TYPE, $action_type);
    }

    public function get_entity_type_image()
    {
        return Theme :: get_image(
            'entity_type/' . $this->get_entity_type(),
            'png',
            null,
            null,
            ToolbarItem :: DISPLAY_ICON,
            false,
            __NAMESPACE__);
    }

    public function get_entity()
    {
        if (! isset($this->entity))
        {
            $this->entity = self :: entity($this->get_entity_type(), $this->get_entity_id());
        }
        return $this->entity;
    }

    public static function entity($entity_type, $entity_id)
    {
        switch ($entity_type)
        {
            case 1 :
                return \Chamilo\Core\User\storage\DataManager :: retrieve(User :: class_name(), (int) $entity_id);
                break;
            case 2 :
                return \Chamilo\Core\Group\storage\DataManager :: retrieve_by_id(Group :: class_name(), $entity_id);
                break;
        }
    }

    public function get_entity_name()
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

    public static function entity_name($entity_type, $entity_id)
    {
        switch ($entity_type)
        {
            case 1 :
                return self :: entity($entity_type, $entity_id)->get_fullname();
                break;
            case 2 :
                return self :: entity($entity_type, $entity_id)->get_name();
                break;
        }
    }

    public function get_role()
    {
        if (! isset($this->role))
        {
            $this->role = \Ehb\Application\Atlantis\Role\DataManager :: retrieve(
                \Ehb\Application\Atlantis\Role\DataClass\Role :: class_name(),
                (int) $this->get_role_id());
        }
        return $this->role;
    }

    public function get_context()
    {
        if (! isset($this->context))
        {
            $this->context = \Chamilo\Core\Group\storage\DataManager :: retrieve(
                Group :: class_name(),
                (int) $this->get_context_id());
        }
        return $this->context;
    }
}
