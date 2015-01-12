<?php
namespace application\discovery\data_source\bamaflex;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DataManager extends \libraries\storage\data_manager\DataManager
{
    const PREFIX = 'discovery_bamaflex_';

    public static function get_type()
    {
        return 'doctrine';
    }
    
    // TODO : copy from DoctrineDataManager
    public function retrieve_history_by_conditions($condition)
    {
        return $this->retrieve_objects(History :: get_table_name(), $condition, null, null, array(), History :: CLASS_NAME);
        return $this->retrieve_object(History :: get_table_name(), $condition, array(), History :: CLASS_NAME);
    }
}
