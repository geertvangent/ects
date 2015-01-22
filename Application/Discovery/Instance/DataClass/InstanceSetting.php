<?php
namespace Ehb\Application\Discovery\Instance\DataClass;

use Ehb\Application\Discovery\Instance\DataManager;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Architecture\ClassnameUtilities;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class InstanceSetting extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_INSTANCE_ID = 'instance_id';
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
    public static function get_default_property_names()
    {
        return parent :: get_default_property_names(
            array(self :: PROPERTY_INSTANCE_ID, self :: PROPERTY_VARIABLE, self :: PROPERTY_VALUE));
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    /**
     *
     * @return int
     */
    public function get_instance_id()
    {
        return $this->get_default_property(self :: PROPERTY_INSTANCE_ID);
    }

    /**
     *
     * @return string
     */
    public function get_variable()
    {
        return $this->get_default_property(self :: PROPERTY_VARIABLE);
    }

    /**
     *
     * @return string
     */
    public function get_value()
    {
        return $this->get_default_property(self :: PROPERTY_VALUE);
    }

    public function set_instance_id($instance_id)
    {
        $this->set_default_property(self :: PROPERTY_INSTANCE_ID, $instance_id);
    }

    public function set_variable($variable)
    {
        $this->set_default_property(self :: PROPERTY_VARIABLE, $variable);
    }

    public function set_value($value)
    {
        $this->set_default_property(self :: PROPERTY_VALUE, $value);
    }

    /**
     *
     * @return string
     */
    public static function get_class_name()
    {
        return self :: CLASS_NAME;
    }

    /**
     *
     * @param Instance $instance
     * @return boolean
     */
    public static function initialize(Instance $instance)
    {
        $settings_file = ClassnameUtilities :: getInstance()->namespaceToFullPath($instance->get_type()) .
             'php/settings/settings.xml';
        $doc = new \DOMDocument();

        $doc->load($settings_file);
        $object = $doc->getElementsByTagname('application')->item(0);
        $settings = $doc->getElementsByTagname('setting');

        foreach ($settings as $index => $setting)
        {
            $external_setting = new InstanceSetting();
            $external_setting->set_instance_id($instance->get_id());
            $external_setting->set_variable($setting->getAttribute('name'));
            $external_setting->set_value($setting->getAttribute('default'));

            if (! $external_setting->create())
            {
                return false;
            }
        }

        return true;
    }

    public function delete()
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
     * @param int $instance_id
     * @return mixed
     */
    public static function get($variable, $instance_id)
    {
        if (! isset(self :: $settings[$instance_id]))
        {
            self :: load($instance_id);
        }

        return (isset(self :: $settings[$instance_id][$variable]) ? self :: $settings[$instance_id][$variable] : null);
    }

    /**
     *
     * @param int $instance_id
     * @return multitype:string
     */
    public static function get_all($instance_id)
    {
        if (! isset(self :: $settings[$instance_id]))
        {
            self :: load($instance_id);
        }

        return self :: $settings[$instance_id];
    }

    /**
     *
     * @param int $instance_id
     */
    public static function load($instance_id)
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(self :: class_name(), self :: PROPERTY_INSTANCE_ID),
            new StaticConditionVariable($instance_id));
        $settings = DataManager :: retrieves(self :: class_name(), new DataClassRetrievesParameters($condition));

        while ($setting = $settings->next_result())
        {
            self :: $settings[$instance_id][$setting->get_variable()] = $setting->get_value();
        }
    }
}
