<?php
namespace application\discovery\data_source;

use libraries\EqualityCondition;
use libraries\AndCondition;
use libraries\DataClassRetrieveParameters;

class DataManager extends \libraries\DataManager
{
    const PREFIX = 'discovery_data_source_';

    /**
     * Gets the type of DataManager to be instantiated
     * 
     * @return string
     */
    public static function get_type()
    {
        return 'doctrine';
    }

    public static function retrieve_instance_setting_from_variable_name($variable, $instance_id)
    {
        $conditions = array();
        $conditions[] = new EqualityCondition(InstanceSetting :: PROPERTY_INSTANCE_ID, $instance_id);
        $conditions[] = new EqualityCondition(InstanceSetting :: PROPERTY_VARIABLE, $variable);
        $condition = new AndCondition($conditions);
        
        return self :: retrieve(InstanceSetting :: class_name(), new DataClassRetrieveParameters($condition));
    }
}
