<?php
namespace Ehb\Application\Discovery\Instance\Storage\DataClass;

use Ehb\Application\Discovery\Instance\Storage\DataManager;
use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

/**
 *
 * @package application.discovery
 */
class Instance extends DataClass
{
    const PROPERTY_TITLE = 'title';
    const PROPERTY_DESCRIPTION = 'description';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_CONTENT_TYPE = 'content_type';
    const TYPE_DISABLED = 0;
    const TYPE_USER = 1;
    const TYPE_INFORMATION = 2;
    const TYPE_DETAILS = 3;
    const PROPERTY_DISPLAY_ORDER = 'display_order';

    public function set_title($title)
    {
        $this->set_default_property(self :: PROPERTY_TITLE, $title);
    }

    public function get_title()
    {
        return $this->get_default_property(self :: PROPERTY_TITLE);
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

    /**
     *
     * @param integer $content_type
     */
    public function set_content_type($content_type)
    {
        $this->set_default_property(self :: PROPERTY_CONTENT_TYPE, $content_type);
    }

    /**
     *
     * @return integer
     */
    public function get_content_type()
    {
        return $this->get_default_property(self :: PROPERTY_CONTENT_TYPE);
    }

    /**
     *
     * @param integer $display_order
     */
    public function set_display_order($display_order)
    {
        $this->set_default_property(self :: PROPERTY_DISPLAY_ORDER, $display_order);
    }

    /**
     *
     * @return integer
     */
    public function get_display_order()
    {
        return $this->get_default_property(self :: PROPERTY_DISPLAY_ORDER);
    }

    public function is_enabled()
    {
        return $this->get_content_type() != self :: TYPE_DISABLED;
    }

    public static function get_default_property_names()
    {
        return parent :: get_default_property_names(
            array(
                self :: PROPERTY_TITLE,
                self :: PROPERTY_DESCRIPTION,
                self :: PROPERTY_TYPE,
                self :: PROPERTY_CONTENT_TYPE,
                self :: PROPERTY_DISPLAY_ORDER));
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

    public function get_module_type()
    {
        if ($this->get_type())
        {
            $namespace = '\\' . $this->get_type() . '\Module';
            return $namespace :: get_type();
        }
        else
        {
            return self :: TYPE_DISABLED;
        }
    }
}
