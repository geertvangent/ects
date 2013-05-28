<?php
namespace application\discovery\module\student_materials\implementation\bamaflex;

use common\libraries\Translation;
use common\libraries\ObjectTableOrder;
use common\libraries\EqualityCondition;
use common\libraries\DataClassRetrievesParameters;

class SettingsConnector
{

    public static function get_data_sources()
    {
        $condition = new EqualityCondition(
            \application\discovery\data_source\Instance :: PROPERTY_TYPE,
            'application\discovery\data_source\bamaflex');
        $instances = \application\discovery\data_source\DataManager :: retrieves(
            \application\discovery\data_source\Instance :: class_name(),
            new DataClassRetrievesParameters(
                $condition,
                null,
                null,
                array(new ObjectTableOrder(\application\discovery\data_source\Instance :: PROPERTY_NAME))));

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
