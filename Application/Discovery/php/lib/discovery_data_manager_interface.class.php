<?php
namespace application\discovery;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    function initialize();

    function create_storage_unit($name, $properties, $indexes);

    function content_object_is_published($object_id);

    function any_content_object_is_published($object_ids);

    function get_content_object_publication_attributes($object_id, $type = null, $offset = null, $count = null, 
            $order_properties = null);

    function get_content_object_publication_attribute($publication_id);

    function count_publication_attributes($user = null, $object_id = null, $condition = null);

    function delete_content_object_publications($object_id);

    function update_content_object_publication_id($publication_attr);

    function retrieve_module_instance($module_instance_id);

    function retrieve_module_instances($condition = null, $offset = null, $max_objects = null, $order_by = null);

    function count_module_instances($condition = null);

    function create_module_instance_setting(ModuleInstanceSetting $module_instance_setting);

    function update_module_instance_setting(ModuleInstanceSetting $module_instance_setting);

    function delete_module_instance_setting(ModuleInstanceSetting $module_instance_setting);

    function retrieve_module_instance_setting($id);

    function retrieve_module_instance_settings($condition = null, $order_by = array(), $offset = 0, $max_objects = -1);

    function retrieve_module_instance_setting_from_variable_name($variable, $module_instance_id);

    function update_module_instance($module_instance);

    function delete_module_instance($module_instance);

    function retrieve_active_module_instances($types = array());

    function retrieve_data_source_instance($data_source_instance_id);

    function retrieve_data_source_instances($condition = null, $offset = null, $max_objects = null, $order_by = null);

    function count_data_source_instances($condition = null);

    function create_data_source_instance_setting(DataSourceInstanceSetting $data_source_instance_setting);

    function update_data_source_instance_setting(DataSourceInstanceSetting $data_source_instance_setting);

    function delete_data_source_instance_setting(DataSourceInstanceSetting $data_source_instance_setting);

    function retrieve_data_source_instance_setting($id);

    function retrieve_data_source_instance_settings($condition = null, $order_by = array(), $offset = 0, $max_objects = -1);

    function retrieve_data_source_instance_setting_from_variable_name($variable, $data_source_instance_id);

    function update_data_source_instance($data_source_instance);

    function delete_data_source_instance($data_source_instance);
}
