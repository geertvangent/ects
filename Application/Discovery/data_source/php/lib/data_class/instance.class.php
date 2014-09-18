<?php
namespace application\discovery\data_source;

use libraries\DataClass;
use libraries\EqualityCondition;
use libraries\DataClassRetrievesParameters;
use libraries\DataClassCountParameters;
use libraries\StaticConditionVariable;
use libraries\PropertyConditionVariable;

/**
 *
 * @package application.discovery
 */
class Instance extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_NAME = 'name';
    const PROPERTY_DESCRIPTION = 'description';
    const PROPERTY_TYPE = 'type';

    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    public function set_description($description)
    {
        $this->set_default_property(self :: PROPERTY_DESCRIPTION, $description);
    }

    public function get_description()
    {
        return $this->get_default_property(self :: PROPERTY_DESCRIPTION);
    }

    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    public static function get_default_property_names()
    {
        return parent :: get_default_property_names(
            array(self :: PROPERTY_NAME, self :: PROPERTY_DESCRIPTION, self :: PROPERTY_TYPE));
    }

    public function create()
    {
        if (! parent :: create())
        {
            return false;
        }
        else
        {
            if (! InstanceSetting :: initialize($this))
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
            $condition = new EqualityCondition(
                new PropertyConditionVariable(InstanceSetting :: class_name(), InstanceSetting :: PROPERTY_INSTANCE_ID), 
                new StaticConditionVariable($this->get_id()));
            $settings = DataManager :: retrieves(
                InstanceSetting :: class_name(), 
                new DataClassRetrievesParameters($condition));
            
            while ($setting = $settings->next_result())
            {
                if (! $setting->delete())
                {
                    return false;
                }
            }
        }
        
        return true;
    }

    public function activate()
    {
        $this->set_content_type($this->get_module_type());
    }

    public function deactivate()
    {
        $this->set_content_type(self :: TYPE_DISABLED);
    }

    public function has_settings()
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(InstanceSetting :: class_name(), InstanceSetting :: PROPERTY_INSTANCE_ID), 
            new StaticConditionVariable($this->get_id()));
        $settings = DataManager :: count(InstanceSetting :: class_name(), new DataClassCountParameters($condition));
        
        return $settings > 0;
    }

    /**
     *
     * @return multitype:string
     */
    public function get_settings()
    {
        return InstanceSetting :: get_all($this->get_id());
    }

    /**
     *
     * @param string $variable
     * @return string
     */
    public function get_setting($variable)
    {
        return InstanceSetting :: get($variable, $this->get_id());
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    public function has_matching_settings($settings)
    {
        foreach ($settings as $key => $setting)
        {
            if (! $this->get_setting($key) == $setting)
            {
                return false;
            }
        }
        return true;
    }
}
