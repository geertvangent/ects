<?php
namespace application\discovery;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
use common\libraries\InCondition;

use common\libraries\AndCondition;

use common\libraries\EqualityCondition;

use common\libraries\Mdb2Database;

class Mdb2DiscoveryDataManager extends Mdb2Database implements DiscoveryDataManagerInterface
{

    function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_');
    }

    // Publication attributes
    function content_object_is_published($object_id)
    {
        return false;
    }

    function any_content_object_is_published($object_ids)
    {
        return false;
    }

    function get_content_object_publication_attributes($object_id, $type = null, $offset = null, $count = null, $order_properties = null)
    {
        return array();
    }

    function get_content_object_publication_attribute($publication_id)
    {
        return array();
    }

    function count_publication_attributes($user = null, $object_id = null, $condition = null)
    {
        return 0;
    }

    function delete_content_object_publications($object_id)
    {
        return true;
    }

    function update_content_object_publication_id($publication_attr)
    {
        return false;
    }

    function retrieve_discovery_module_instance($discovery_module_instance_id)
    {
        $condition = new EqualityCondition(DiscoveryModuleInstance :: PROPERTY_ID, $discovery_module_instance_id);
        return $this->retrieve_object(DiscoveryModuleInstance :: get_table_name(), $condition, array(), DiscoveryModuleInstance :: CLASS_NAME);
    }

    function retrieve_discovery_module_instances($condition = null, $offset = null, $max_objects = null, $order_by = null)
    {
        return $this->retrieve_objects(DiscoveryModuleInstance :: get_table_name(), $condition, $offset, $max_objects, $order_by, DiscoveryModuleInstance :: CLASS_NAME);
    }

    function count_discovery_module_instances($condition = null)
    {
        return $this->count_objects(DiscoveryModuleInstance :: get_table_name(), $condition);
    }

    function create_discovery_module_instance_setting(DiscoveryModuleInstanceSetting $discovery_module_instance_setting)
    {
        return $this->create($discovery_module_instance_setting);
    }

    function update_discovery_module_instance_setting(DiscoveryModuleInstanceSetting $discovery_module_instance_setting)
    {
        $condition = new EqualityCondition(DiscoveryModuleInstanceSetting :: PROPERTY_ID, $discovery_module_instance_setting->get_id());
        return $this->update($discovery_module_instance_setting, $condition);
    }

    function delete_discovery_module_instance_setting(DiscoveryModuleInstanceSetting $discovery_module_instance_setting)
    {
        $condition = new EqualityCondition(DiscoveryModuleInstanceSetting :: PROPERTY_ID, $discovery_module_instance_setting->get_id());
        return $this->delete($discovery_module_instance_setting->get_table_name(), $condition);
    }

    /**
     * @param int $id
     */
    function retrieve_discovery_module_instance_setting($id)
    {
        $condition = new EqualityCondition(DiscoveryModuleInstanceSetting :: PROPERTY_ID, $id);
        return $this->retrieve_object(DiscoveryModuleInstanceSetting :: get_table_name(), $condition, array(), DiscoveryModuleInstanceSetting :: CLASS_NAME);
    }

    function retrieve_discovery_module_instance_settings($condition = null, $order_by = array(), $offset = 0, $max_objects = -1)
    {
        return $this->retrieve_objects(DiscoveryModuleInstanceSetting :: get_table_name(), $condition, $offset, $max_objects, $order_by, DiscoveryModuleInstanceSetting :: CLASS_NAME);
    }

    function retrieve_discovery_module_instance_setting_from_variable_name($variable, $external_id)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(DiscoveryModuleInstanceSetting :: PROPERTY_EXTERNAL_ID, $external_id);
        $conditions[] = new EqualityCondition(DiscoveryModuleInstanceSetting :: PROPERTY_VARIABLE, $variable);
        $condition = new AndCondition($conditions);
        return $this->retrieve_object(DiscoveryModuleInstanceSetting :: get_table_name(), $condition, array(), DiscoveryModuleInstanceSetting :: CLASS_NAME);
    }

    function update_discovery_module_instance($discovery_module_instance)
    {
        $condition = new EqualityCondition(DiscoveryModuleInstance :: PROPERTY_ID, $discovery_module_instance->get_id());
        return $this->update($discovery_module_instance, $condition);
    }

    function delete_discovery_module_instance($discovery_module_instance)
    {
        $condition = new EqualityCondition(DiscoveryModuleInstance :: PROPERTY_ID, $discovery_module_instance->get_id());
        return $this->delete(DiscoveryModuleInstance :: get_table_name(), $condition);
    }

    function retrieve_active_discovery_module_instances($types = array())
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(DiscoveryModuleInstance :: PROPERTY_ENABLED, 1);

        if (! is_array($types))
        {
            $types = array($types);
        }

        if (count($types) > 0)
        {
            $conditions[] = new InCondition(DiscoveryModuleInstance :: PROPERTY_TYPE, $types);
        }

        $condition = new AndCondition($conditions);

        return $this->retrieve_discovery_module_instances($condition);
    }
}
?>