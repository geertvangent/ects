<?php
namespace application\discovery;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    public function initialize();

    public function create_storage_unit($name, $properties, $indexes);

    public function content_object_is_published($object_id);

    public function any_content_object_is_published($object_ids);

    public function get_content_object_publication_attributes($object_id, $type = null, $offset = null, $count = null, 
        $order_properties = null);

    public function get_content_object_publication_attribute($publication_id);

    public function count_publication_attributes($user = null, $object_id = null, $condition = null);

    public function delete_content_object_publications($object_id);

    public function update_content_object_publication_id($publication_attr);

    public function retrieve_module_instance($module_instance_id);

    public function retrieve_module_instances($condition = null, $offset = null, $max_objects = null, $order_by = null);

    public function count_module_instances($condition = null);

    public function create_module_instance_setting(InstanceSetting $module_instance_setting);

    public function update_module_instance_setting(InstanceSetting $module_instance_setting);

    public function delete_module_instance_setting(InstanceSetting $module_instance_setting);

    public function retrieve_module_instance_setting($id);

    public function retrieve_module_instance_settings($condition = null, $order_by = array(), $offset = 0, $max_objects = -1);

    public function retrieve_module_instance_setting_from_variable_name($variable, $module_instance_id);

    public function update_module_instance($module_instance);

    public function delete_module_instance($module_instance);

    public function retrieve_active_module_instances($types = array());

    public function retrieve_data_source_instance($data_source_instance_id);

    public function retrieve_data_source_instances($condition = null, $offset = null, $max_objects = null, $order_by = null);

    public function count_data_source_instances($condition = null);

    public function create_data_source_instance_setting(
        \application\discovery\data_source\InstanceSetting $data_source_instance_setting);

    public function update_data_source_instance_setting(
        \application\discovery\data_source\InstanceSetting $data_source_instance_setting);

    public function delete_data_source_instance_setting(
        \application\discovery\data_source\InstanceSetting $data_source_instance_setting);

    public function retrieve_data_source_instance_setting($id);

    public function retrieve_data_source_instance_settings($condition = null, $order_by = array(), $offset = 0, $max_objects = -1);

    public function retrieve_data_source_instance_setting_from_variable_name($variable, $data_source_instance_id);

    public function update_data_source_instance($data_source_instance);

    public function delete_data_source_instance($data_source_instance);
}
