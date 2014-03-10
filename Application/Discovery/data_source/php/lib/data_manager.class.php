<?php
namespace application\discovery\data_source;

use common\libraries\EqualityCondition;
use common\libraries\AndCondition;
use common\libraries\DataClassRetrieveParameters;

class DataManager extends \common\libraries\DataManager
{

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
