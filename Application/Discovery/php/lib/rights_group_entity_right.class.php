<?php
namespace application\discovery;

use libraries\DataClass;
use libraries\Utilities;

class RightsGroupEntityRight extends DataClass
{
    // Keep track of the context so we know which table to call
    private $context;
    const CLASS_NAME = __CLASS__;
    const PROPERTY_ENTITY_ID = 'entity_id';
    const PROPERTY_ENTITY_TYPE = 'entity_type';
    const PROPERTY_GROUP_ID = 'group_id';
    const PROPERTY_RIGHT_ID = 'right_id';
    const PROPERTY_MODULE_ID = 'module_id';

    public static function get_default_property_names()
    {
        return parent :: get_default_property_names(
            array(
                self :: PROPERTY_RIGHT_ID, 
                self :: PROPERTY_ENTITY_ID, 
                self :: PROPERTY_ENTITY_TYPE, 
                self :: PROPERTY_GROUP_ID, 
                self :: PROPERTY_MODULE_ID));
    }

    public function get_context()
    {
        return $this->context;
    }

    public function set_context($context)
    {
        $this->context = $context;
    }

    public function get_entity_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENTITY_ID);
    }

    public function set_entity_id($entity_id)
    {
        $this->set_default_property(self :: PROPERTY_ENTITY_ID, $entity_id);
    }

    public function get_entity_type()
    {
        return $this->get_default_property(self :: PROPERTY_ENTITY_TYPE);
    }

    public function set_entity_type($entity_type)
    {
        $this->set_default_property(self :: PROPERTY_ENTITY_TYPE, $entity_type);
    }

    public function get_group_id()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP_ID);
    }

    public function set_group_id($group_id)
    {
        $this->set_default_property(self :: PROPERTY_GROUP_ID, $group_id);
    }

    public function get_right_id()
    {
        return $this->get_default_property(self :: PROPERTY_RIGHT_ID);
    }

    public function set_right_id($right_id)
    {
        $this->set_default_property(self :: PROPERTY_RIGHT_ID, $right_id);
    }

    public function set_module_id($module_id)
    {
        $this->set_default_property(self :: PROPERTY_MODULE_ID, $module_id);
    }

    public function get_module_id()
    {
        return $this->get_default_property(self :: PROPERTY_MODULE_ID);
    }

    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    public function get_string()
    {
        return $this->get_module_id() . '_' . $this->get_entity_type() . '_' . $this->get_entity_id() . '_' .
             $this->get_group_id();
    }
}
