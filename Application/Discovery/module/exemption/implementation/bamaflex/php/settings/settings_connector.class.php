<?php
namespace application\discovery\module\exemption\implementation\bamaflex;

use common\libraries\Translation;
use common\libraries\ObjectTableOrder;
use common\libraries\EqualityCondition;
use application\discovery\DataSourceInstance;

class SettingsConnector
{

    public static function get_data_sources()
    {
        $condition = new EqualityCondition(DataSourceInstance :: PROPERTY_TYPE, 
                'application\discovery\data_source\bamaflex');
        $instances = \application\discovery\DataManager :: get_instance()->retrieve_data_source_instances($condition, 
                null, null, array(new ObjectTableOrder(DataSourceInstance :: PROPERTY_NAME)));
        
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
