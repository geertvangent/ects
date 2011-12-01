<?php
namespace application\discovery;

use common\libraries\DataClass;
use common\libraries\Utilities;
use common\libraries\EqualityCondition;
use common\libraries\AndCondition;

/**
 * @package application.discovery
 */
class DataSourceInstance extends DataClass
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_NAME = 'name';
    const PROPERTY_DESCRIPTION = 'description';
    const PROPERTY_TYPE = 'type';

    function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
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

    static function get_default_property_names()
    {
        return parent :: get_default_property_names(array(self :: PROPERTY_NAME, self :: PROPERTY_DESCRIPTION, 
                self :: PROPERTY_TYPE));
    }

    /**
     * @return string
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    public function create()
    {
        if (! parent :: create())
        {
            return false;
        }
        else
        {
            if (! DataSourceInstanceSetting :: initialize($this))
            {
                return false;
            }
        }
        
        //        $succes = RepositoryRights :: get_instance()->create_location_in_external_instances_subtree($this->get_title(), $this->get_id(), RepositoryRights :: get_instance()->get_external_instances_subtree_root_id());
        //        if (! $succes)
        //        {
        //            return false;
        //        }
        

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
            $condition = new EqualityCondition(DataSourceInstanceSetting :: PROPERTY_DATA_SOURCE_INSTANCE_ID, $this->get_id());
            $settings = $this->get_data_manager()->retrieve_data_source_instance_settings($condition);
            
            while ($setting = $settings->next_result())
            {
                if (! $setting->delete())
                {
                    return false;
                }
            }
        }
        
        //        $location = RepositoryRights :: get_instance()->get_location_by_identifier_from_external_instances_subtree($this->get_id());
        //        if ($location)
        //        {
        //            if (! $location->remove())
        //            {
        //                return false;
        //            }
        //        }
        

        return true;
    }

    public function activate()
    {
        $this->set_enabled(true);
    }

    public function deactivate()
    {
        $this->set_enabled(false);
    }

    public function has_settings()
    {
        $condition = new EqualityCondition(DataSourceInstanceSetting :: PROPERTY_MODULE_INSTANCE_ID, $this->get_id());
        $settings = DiscoveryDataManager :: get_instance()->count_data_source_instance_settings($condition);
        
        return $settings > 0;
    }

    /**
     * @return multitype:string
     */
    public function get_settings()
    {
        return DataSourceInstanceSetting :: get_all($this->get_id());
    }

    /**
     * @param string $variable
     * @return string
     */
    public function get_setting($variable)
    {
        return DataSourceInstanceSetting :: get($variable, $this->get_id());
    }

    /**
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

}
?>