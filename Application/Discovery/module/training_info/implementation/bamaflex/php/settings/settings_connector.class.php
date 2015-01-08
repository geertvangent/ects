<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

use libraries\platform\translation\Translation;
use libraries\storage\PropertyConditionVariable;
use libraries\storage\EqualityCondition;
use libraries\storage\DataClassRetrievesParameters;
use libraries\storage\OrderBy;
use libraries\storage\StaticConditionVariable;

class SettingsConnector
{

    public static function get_data_sources()
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                \application\discovery\data_source\Instance :: class_name(), 
                \application\discovery\data_source\Instance :: PROPERTY_TYPE), 
            new StaticConditionVariable('application\discovery\data_source\bamaflex'));
        $instances = \application\discovery\data_source\DataManager :: retrieves(
            \application\discovery\data_source\Instance :: class_name(),
            new DataClassRetrievesParameters(
                $condition,
                null,
                null,
                array(
                    new OrderBy(
                        new PropertyConditionVariable(
                            \application\discovery\data_source\Instance :: class_name(),
                            \application\discovery\data_source\Instance :: PROPERTY_NAME)))));

        $data_sources = array();

        if ($instances->size() == 0)
        {
            $data_sources[0] = Translation :: get('AddConnectionInstanceFirst');
        }
        else
        {
            while ($instance = $instances->next_result())
            {
                $data_sources[$instance->get_id()] = $instance->get_name();
            }
        }

        return $data_sources;
    }
}
