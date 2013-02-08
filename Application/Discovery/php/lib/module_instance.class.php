<?php
namespace application\discovery;

use common\libraries\DataClass;
use common\libraries\EqualityCondition;

/**
 *
 * @package application.discovery
 */
class ModuleInstance extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const TABLE_NAME = 'module_instance';
    const PROPERTY_TITLE = 'title';
    const PROPERTY_DESCRIPTION = 'description';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_CONTENT_TYPE = 'content_type';
    const TYPE_DISABLED = 0;
    const TYPE_USER = 1;
    const TYPE_INFORMATION = 2;
    const TYPE_DETAILS = 3;
    const PROPERTY_DISPLAY_ORDER = 'display_order';

    function set_title($title)
    {
        $this->set_default_property(self :: PROPERTY_TITLE, $title);
    }

    function get_title()
    {
        return $this->get_default_property(self :: PROPERTY_TITLE);
    }

    function set_description($description)
    {
        $this->set_default_property(self :: PROPERTY_DESCRIPTION, $description);
    }

    function get_description()
    {
        return $this->get_default_property(self :: PROPERTY_DESCRIPTION);
    }

    function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     *
     * @param integer $content_type
     */
    function set_content_type($content_type)
    {
        $this->set_default_property(self :: PROPERTY_CONTENT_TYPE, $content_type);
    }

    /**
     *
     * @return integer
     */
    function get_content_type()
    {
        return $this->get_default_property(self :: PROPERTY_CONTENT_TYPE);
    }

    /**
     *
     * @param integer $display_order
     */
    function set_display_order($display_order)
    {
        $this->set_default_property(self :: PROPERTY_DISPLAY_ORDER, $display_order);
    }

    /**
     *
     * @return integer
     */
    function get_display_order()
    {
        return $this->get_default_property(self :: PROPERTY_DISPLAY_ORDER);
    }

    function is_enabled()
    {
        return $this->get_content_type() != self :: TYPE_DISABLED;
    }

    static function get_default_property_names()
    {
        return parent :: get_default_property_names(
                array(self :: PROPERTY_TITLE, self :: PROPERTY_DESCRIPTION, self :: PROPERTY_TYPE,
                        self :: PROPERTY_CONTENT_TYPE, self :: PROPERTY_DISPLAY_ORDER));
    }

    static function get_table_name()
    {
        return self :: TABLE_NAME;
    }

    public function create()
    {
        if (! parent :: create())
        {
            return false;
        }
        else
        {
            if (! ModuleInstanceSetting :: initialize($this))
            {
                return false;
            }
        }
        return DiscoveryDataManager :: create_module_rights_storage_units($this);
    }

    public function delete()
    {
        if (! parent :: delete())
        {
            return false;
        }
        else
        {
            $condition = new EqualityCondition(ModuleInstanceSetting :: PROPERTY_MODULE_INSTANCE_ID, $this->get_id());
            $settings = $this->get_data_manager()->retrieve_module_instance_settings($condition);

            while ($setting = $settings->next_result())
            {
                if (! $setting->delete())
                {
                    return false;
                }
            }
        }

        // $location = RepositoryRights ::
        // get_instance()->get_location_by_identifier_from_external_instances_subtree($this->get_id());
        // if ($location)
        // {
        // if (! $location->remove())
        // {
        // return false;
        // }
        // }

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
        $condition = new EqualityCondition(ModuleInstanceSetting :: PROPERTY_MODULE_INSTANCE_ID, $this->get_id());
        $settings = DiscoveryDataManager :: get_instance()->count_module_instance_settings($condition);

        return $settings > 0;
    }

    /**
     *
     * @return multitype:string
     */
    public function get_settings()
    {
        return ModuleInstanceSetting :: get_all($this->get_id());
    }

    /**
     *
     * @param string $variable
     * @return string
     */
    public function get_setting($variable)
    {
        return ModuleInstanceSetting :: get($variable, $this->get_id());
    }

    /**
     *
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    function has_matching_settings($settings)
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

    function get_module_type()
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
