<?php
namespace application\discovery;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
use common\libraries\InCondition;
use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\DoctrineDatabase;

class DoctrineDataManager extends DoctrineDatabase implements DataManagerInterface
{

    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_');
    }
    
    // Publication attributes
    public function content_object_is_published($object_id)
    {
        return false;
    }

    public function any_content_object_is_published($object_ids)
    {
        return false;
    }

    public function get_content_object_publication_attributes($object_id, $type = null, $offset = null, $count = null, 
            $order_properties = null)
    {
        return array();
    }

    public function get_content_object_publication_attribute($publication_id)
    {
        return array();
    }

    public function count_publication_attributes($user = null, $object_id = null, $condition = null)
    {
        return 0;
    }

    public function delete_content_object_publications($object_id)
    {
        return true;
    }

    public function update_content_object_publication_id($publication_attr)
    {
        return false;
    }

    public function retrieve_module_instance($module_instance_id)
    {
        $condition = new EqualityCondition(ModuleInstance :: PROPERTY_ID, $module_instance_id);
        return $this->retrieve_object(ModuleInstance :: get_table_name(), $condition, array(), ModuleInstance :: CLASS_NAME);
    }

    public function retrieve_module_instance_by_condition($condition, $order_by)
    {
        return $this->retrieve_object(ModuleInstance :: get_table_name(), $condition, $order_by, 
                ModuleInstance :: CLASS_NAME);
    }

    public function retrieve_module_instances($condition = null, $offset = null, $max_objects = null, $order_by = null)
    {
        return $this->retrieve_objects(ModuleInstance :: get_table_name(), $condition, $offset, $max_objects, $order_by, 
                ModuleInstance :: CLASS_NAME);
    }

    public function count_module_instances($condition = null)
    {
        return $this->count_objects(ModuleInstance :: get_table_name(), $condition);
    }

    public function create_module_instance_setting(ModuleInstanceSetting $module_instance_setting)
    {
        return $this->create($module_instance_setting);
    }

    public function update_module_instance_setting(ModuleInstanceSetting $module_instance_setting)
    {
        $condition = new EqualityCondition(ModuleInstanceSetting :: PROPERTY_ID, $module_instance_setting->get_id());
        return $this->update($module_instance_setting, $condition);
    }

    public function delete_module_instance_setting(ModuleInstanceSetting $module_instance_setting)
    {
        $condition = new EqualityCondition(ModuleInstanceSetting :: PROPERTY_ID, $module_instance_setting->get_id());
        return $this->delete($module_instance_setting->get_table_name(), $condition);
    }

    /**
     *
     * @param $id int
     */
    public function retrieve_module_instance_setting($id)
    {
        $condition = new EqualityCondition(ModuleInstanceSetting :: PROPERTY_ID, $id);
        return $this->retrieve_object(ModuleInstanceSetting :: get_table_name(), $condition, array(), 
                ModuleInstanceSetting :: CLASS_NAME);
    }

    public function retrieve_module_instance_settings($condition = null, $order_by = array(), $offset = 0, $max_objects = -1)
    {
        return $this->retrieve_objects(ModuleInstanceSetting :: get_table_name(), $condition, $offset, $max_objects, 
                $order_by, ModuleInstanceSetting :: CLASS_NAME);
    }

    public function retrieve_module_instance_setting_from_variable_name($variable, $module_instance_id)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(ModuleInstanceSetting :: PROPERTY_MODULE_INSTANCE_ID, $module_instance_id);
        $conditions[] = new EqualityCondition(ModuleInstanceSetting :: PROPERTY_VARIABLE, $variable);
        $condition = new AndCondition($conditions);
        return $this->retrieve_object(ModuleInstanceSetting :: get_table_name(), $condition, array(), 
                ModuleInstanceSetting :: CLASS_NAME);
    }

    public function update_module_instance($module_instance)
    {
        $condition = new EqualityCondition(ModuleInstance :: PROPERTY_ID, $module_instance->get_id());
        return $this->update($module_instance, $condition);
    }

    public function delete_module_instance($module_instance)
    {
        $condition = new EqualityCondition(ModuleInstance :: PROPERTY_ID, $module_instance->get_id());
        return $this->delete(ModuleInstance :: get_table_name(), $condition);
    }

    public function delete_rights_group_entity_rights($right_id)
    {
        $condition = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_ID, $right_id);
        return $this->delete(RightsGroupEntityRight :: get_table_name(), $condition);
    }

    public function retrieve_active_module_instances($types = array())
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(ModuleInstance :: PROPERTY_ENABLED, 1);
        
        if (! is_array($types))
        {
            $types = array($types);
        }
        
        if (count($types) > 0)
        {
            $conditions[] = new InCondition(ModuleInstance :: PROPERTY_TYPE, $types);
        }
        
        $condition = new AndCondition($conditions);
        
        return $this->retrieve_module_instances($condition);
    }

    public function count_module_instance_settings($conditions = null)
    {
        return $this->count_objects(ModuleInstance :: get_table_name(), $conditions);
    }

    public function retrieve_data_source_instance($data_source_instance_id)
    {
        $condition = new EqualityCondition(DataSourceInstance :: PROPERTY_ID, $data_source_instance_id);
        return $this->retrieve_object(DataSourceInstance :: get_table_name(), $condition, array(), 
                DataSourceInstance :: CLASS_NAME);
    }

    public function retrieve_data_source_instances($condition = null, $offset = null, $max_objects = null, $order_by = null)
    {
        return $this->retrieve_objects(DataSourceInstance :: get_table_name(), $condition, $offset, $max_objects, 
                $order_by, DataSourceInstance :: CLASS_NAME);
    }

    public function count_data_source_instances($condition = null)
    {
        return $this->count_objects(DataSourceInstance :: get_table_name(), $condition);
    }

    public function create_data_source_instance_setting(DataSourceInstanceSetting $data_source_instance_setting)
    {
        return $this->create($data_source_instance_setting);
    }

    public function update_data_source_instance_setting(DataSourceInstanceSetting $data_source_instance_setting)
    {
        $condition = new EqualityCondition(DataSourceInstanceSetting :: PROPERTY_ID, 
                $data_source_instance_setting->get_id());
        return $this->update($data_source_instance_setting, $condition);
    }

    public function delete_data_source_instance_setting(DataSourceInstanceSetting $data_source_instance_setting)
    {
        $condition = new EqualityCondition(DataSourceInstanceSetting :: PROPERTY_ID, 
                $data_source_instance_setting->get_id());
        return $this->delete($data_source_instance_setting->get_table_name(), $condition);
    }

    /**
     *
     * @param $id int
     */
    public function retrieve_data_source_instance_setting($id)
    {
        $condition = new EqualityCondition(DataSourceInstanceSetting :: PROPERTY_ID, $id);
        return $this->retrieve_object(DataSourceInstanceSetting :: get_table_name(), $condition, array(), 
                DataSourceInstanceSetting :: CLASS_NAME);
    }

    public function retrieve_data_source_instance_settings($condition = null, $order_by = array(), $offset = 0, $max_objects = -1)
    {
        return $this->retrieve_objects(DataSourceInstanceSetting :: get_table_name(), $condition, $offset, $max_objects, 
                $order_by, DataSourceInstanceSetting :: CLASS_NAME);
    }

    public function retrieve_rights_group_entity_rights($condition = null, $order_by = array(), $offset = 0, $max_objects = -1)
    {
        return $this->retrieve_objects(RightsGroupEntityRight :: get_table_name(), $condition, $offset, $max_objects, 
                $order_by, RightsGroupEntityRight :: CLASS_NAME);
    }

    public function retrieve_data_source_instance_setting_from_variable_name($variable, $data_source_instance_id)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(DataSourceInstanceSetting :: PROPERTY_DATA_SOURCE_INSTANCE_ID, 
                $data_source_instance_id);
        $conditions[] = new EqualityCondition(DataSourceInstanceSetting :: PROPERTY_VARIABLE, $variable);
        $condition = new AndCondition($conditions);
        return $this->retrieve_object(DataSourceInstanceSetting :: get_table_name(), $condition, array(), 
                DataSourceInstanceSetting :: CLASS_NAME);
    }

    public function update_data_source_instance($data_source_instance)
    {
        $condition = new EqualityCondition(DataSourceInstance :: PROPERTY_ID, $data_source_instance->get_id());
        return $this->update($data_source_instance, $condition);
    }

    public function delete_data_source_instance($data_source_instance)
    {
        $condition = new EqualityCondition(DataSourceInstance :: PROPERTY_ID, $data_source_instance->get_id());
        return $this->delete(DataSourceInstance :: get_table_name(), $condition);
    }

    public function count_data_source_instance_settings($conditions = null)
    {
        return $this->count_objects(DataSourceInstance :: get_table_name(), $conditions);
    }

    public function count_rights_group_entity_rights($conditions = null)
    {
        return $this->count_objects(RightsGroupEntityRight :: get_table_name(), $conditions);
    }
}
