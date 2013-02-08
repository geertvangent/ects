<?php
namespace application\discovery;

use common\libraries\Utilities;
use common\libraries\EqualityCondition;
use common\libraries\Path;
use common\libraries\DataClass;
use DOMDocument;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DataSourceInstanceSetting extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_DATA_SOURCE_INSTANCE_ID = 'data_source_instance_id';
    const PROPERTY_VARIABLE = 'variable';
    const PROPERTY_VALUE = 'value';

    /**
     * A static array containing all settings of discovery module instances
     *
     * @var array
     */
    private static $settings;

    /**
     * Get the default properties of all settings.
     *
     * @return array The property names.
     */
    static function get_default_property_names()
    {
        return parent :: get_default_property_names(
                array(self :: PROPERTY_DATA_SOURCE_INSTANCE_ID, self :: PROPERTY_VARIABLE, self :: PROPERTY_VALUE));
    }

    /**
     *
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     *
     * @return int
     */
    function get_data_source_instance_id()
    {
        return $this->get_default_property(self :: PROPERTY_DATA_SOURCE_INSTANCE_ID);
    }

    /**
     *
     * @return string
     */
    function get_variable()
    {
        return $this->get_default_property(self :: PROPERTY_VARIABLE);
    }

    /**
     *
     * @return string
     */
    function get_value()
    {
        return $this->get_default_property(self :: PROPERTY_VALUE);
    }

    function set_data_source_instance_id($data_source_instance_id)
    {
        $this->set_default_property(self :: PROPERTY_DATA_SOURCE_INSTANCE_ID, $data_source_instance_id);
    }

    function set_variable($variable)
    {
        $this->set_default_property(self :: PROPERTY_VARIABLE, $variable);
    }

    function set_value($value)
    {
        $this->set_default_property(self :: PROPERTY_VALUE, $value);
    }

    /**
     *
     * @return string
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    /**
     *
     * @return string
     */
    static function get_class_name()
    {
        return self :: CLASS_NAME;
    }

    /**
     *
     * @param DataSourceInstance $data_source_instance
     * @return boolean
     */
    static function initialize(DataSourceInstance $data_source_instance)
    {
        $settings_file = Path :: namespace_to_full_path($data_source_instance->get_type()) . Utilities :: get_package_name_from_namespace(
                $data_source_instance->get_type()) . '.xml';

        $doc = new DOMDocument();

        $doc->load($settings_file);
        $object = $doc->getElementsByTagname('application')->item(0);
        $settings = $doc->getElementsByTagname('setting');

        foreach ($settings as $index => $setting)
        {
            $external_setting = new DataSourceInstanceSetting();
            $external_setting->set_data_source_instance_id($data_source_instance->get_id());
            $external_setting->set_variable($setting->getAttribute('name'));
            $external_setting->set_value($setting->getAttribute('default'));

            if (! $external_setting->create())
            {
                return false;
            }
        }

        return true;
    }

    function delete()
    {
        if (! parent :: delete())
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     *
     * @param string $variable
     * @param int $data_source_instance_id
     * @return mixed
     */
    static function get($variable, $data_source_instance_id)
    {
        if (! isset(self :: $settings[$data_source_instance_id]))
        {
            self :: load($data_source_instance_id);
        }

        return (isset(self :: $settings[$data_source_instance_id][$variable]) ? self :: $settings[$data_source_instance_id][$variable] : null);
    }

    /**
     *
     * @param int $data_source_instance_id
     * @return multitype:string
     */
    static function get_all($data_source_instance_id)
    {
        if (! isset(self :: $settings[$data_source_instance_id]))
        {
            self :: load($data_source_instance_id);
        }

        return self :: $settings[$data_source_instance_id];
    }

    /**
     *
     * @param int $data_source_instance_id
     */
    static function load($data_source_instance_id)
    {
        $condition = new EqualityCondition(self :: PROPERTY_DATA_SOURCE_INSTANCE_ID, $data_source_instance_id);
        $settings = DiscoveryDataManager :: get_instance()->retrieve_data_source_instance_settings($condition);

        while ($setting = $settings->next_result())
        {
            self :: $settings[$data_source_instance_id][$setting->get_variable()] = $setting->get_value();
        }
    }
}
