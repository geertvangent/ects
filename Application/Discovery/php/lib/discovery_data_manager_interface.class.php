<?php
namespace application\discovery;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DiscoveryDataManagerInterface
{

    function initialize();

    function create_storage_unit($name, $properties, $indexes);

    function content_object_is_published($object_id);

    function any_content_object_is_published($object_ids);

    function get_content_object_publication_attributes($object_id, $type = null, $offset = null, $count = null, $order_properties = null);

    function get_content_object_publication_attribute($publication_id);

    function count_publication_attributes($user = null, $object_id = null, $condition = null);

    function delete_content_object_publications($object_id);

    function update_content_object_publication_id($publication_attr);

    function retrieve_discovery_module_instance($discovery_module_instance_id);

    function retrieve_discovery_module_instances($condition = null, $offset = null, $max_objects = null, $order_by = null);

    function count_discovery_module_instances($condition = null);

    function create_discovery_module_instance_setting(DiscoveryModuleInstanceSetting $discovery_module_instance_setting);

    function update_discovery_module_instance_setting(DiscoveryModuleInstanceSetting $discovery_module_instance_setting);

    function delete_discovery_module_instance_setting(DiscoveryModuleInstanceSetting $discovery_module_instance_setting);

    function retrieve_discovery_module_instance_setting($id);

    function retrieve_discovery_module_instance_settings($condition = null, $order_by = array(), $offset = 0, $max_objects = -1);

    function retrieve_discovery_module_instance_setting_from_variable_name($variable, $external_id);

    function update_discovery_module_instance($discovery_module_instance);

    function delete_discovery_module_instance($discovery_module_instance);

    function retrieve_active_discovery_module_instances($types = array());
}
?>