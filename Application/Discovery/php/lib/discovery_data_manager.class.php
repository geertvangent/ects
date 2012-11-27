<?php
namespace application\discovery;

use rights\RightsUtil;

use common\libraries\Configuration;
use common\libraries\WebApplication;
use common\libraries\Utilities;
use DOMDocument;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DiscoveryDataManager
{
    /**
     * Instance of this class for the singleton pattern.
     */
    private static $instance;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Uses a singleton pattern and a factory pattern to return the data manager. The configuration determines which
     * data manager class is to be instantiated.
     *
     * @return DiscoveryDataManagerInterface The data manager.
     */
    static function get_instance()
    {
        if (! isset(self :: $instance))
        {
            $type = Configuration :: get_instance()->get_parameter('general', 'data_manager');
            $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($type) . 'DiscoveryDataManager';
            self :: $instance = new $class();
        }
        return self :: $instance;
    }

    static function create_module_rights_storage_units($module)
    {
        $right_location = self :: parse_xml_file(
                WebApplication :: get_application_path(DiscoveryManager :: APPLICATION_NAME) . 'php/rights/rights_location.xml');
        $right_location_entity_right = self :: parse_xml_file(
                WebApplication :: get_application_path(DiscoveryManager :: APPLICATION_NAME) . 'php/rights/rights_location_entity_right.xml');

        if (! self :: get_instance()->create_storage_unit($module->get_id() . '_' . $right_location['name'],
                $right_location['properties'], $right_location['indexes']))
        {
            return false;
        }

        $rights = $module->get_type() . '\Rights';
        if (! $rights :: get_instance()->create_subtree_root_location(
                DiscoveryManager :: APPLICATION_NAME . '_' . $module->get_id(), 0, RightsUtil :: TREE_TYPE_ROOT))
        {
            return false;
        }

        if (! self :: get_instance()->create_storage_unit(
                $module->get_id() . '_' . $right_location_entity_right['name'],
                $right_location_entity_right['properties'], $right_location_entity_right['indexes']))
        {
            return false;
        }

        return true;
    }

    public static function parse_xml_file($file)
    {
        $name = '';
        $properties = array();
        $indexes = array();

        $doc = new DOMDocument();
        $doc->load($file);
        $object = $doc->getElementsByTagname('object')->item(0);
        $name = $object->getAttribute('name');
        $xml_properties = $doc->getElementsByTagname('property');
        $attributes = array('type', 'length', 'unsigned', 'notnull', 'default', 'autoincrement', 'fixed');
        foreach ($xml_properties as $index => $property)
        {
            $property_info = array();
            foreach ($attributes as $index => $attribute)
            {
                if ($property->hasAttribute($attribute))
                {
                    $property_info[$attribute] = $property->getAttribute($attribute);
                }
            }
            $properties[$property->getAttribute('name')] = $property_info;
        }
        $xml_indexes = $doc->getElementsByTagname('index');
        foreach ($xml_indexes as $key => $index)
        {
            $index_info = array();
            $index_info['type'] = $index->getAttribute('type');
            $index_properties = $index->getElementsByTagname('indexproperty');
            foreach ($index_properties as $subkey => $index_property)
            {
                $index_info['fields'][$index_property->getAttribute('name')] = array(
                        'length' => $index_property->getAttribute('length'));
            }
            $indexes[$index->getAttribute('name')] = $index_info;
        }
        $result = array();
        $result['name'] = $name;
        $result['properties'] = $properties;
        $result['indexes'] = $indexes;

        return $result;
    }
}
?>