<?php
namespace Chamilo\Application\Discovery\Instance;

use Chamilo\Libraries\Storage\EqualityCondition;
use Chamilo\Libraries\Storage\AndCondition;
use Chamilo\Libraries\Storage\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\PropertyConditionVariable;
use Chamilo\Libraries\Storage\StaticConditionVariable;

class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
{
    const PREFIX = 'discovery_module_';

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
