<?php
namespace Chamilo\Application\Discovery\DataSource;

use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Application\Discovery\DataSource\DataClass\InstanceSetting;

class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
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
