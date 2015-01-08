<?php
namespace application\discovery\data_source;

use libraries\storage\EqualityCondition;
use libraries\storage\AndCondition;
use libraries\storage\DataClassRetrieveParameters;
use libraries\storage\StaticConditionVariable;
use libraries\storage\PropertyConditionVariable;

class DataManager extends \libraries\storage\data_manager\DataManager
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
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(InstanceSetting :: class_name(), InstanceSetting :: PROPERTY_INSTANCE_ID), 
            new StaticConditionVariable($instance_id));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(InstanceSetting :: class_name(), InstanceSetting :: PROPERTY_VARIABLE), 
            new StaticConditionVariable($variable));
        $condition = new AndCondition($conditions);
        
        return self :: retrieve(InstanceSetting :: class_name(), new DataClassRetrieveParameters($condition));
    }
}
