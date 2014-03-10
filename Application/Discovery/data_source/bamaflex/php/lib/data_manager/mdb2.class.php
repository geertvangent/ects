<?php
namespace application\discovery\data_source\bamaflex;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class Mdb2DataManager extends \application\discovery\Mdb2DataManager
{

    public function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_bamaflex_');
    }

    public function retrieve_history_by_conditions($condition)
    {
        return $this->retrieve_objects(History :: get_table_name(), $condition, null, null, array(), History :: CLASS_NAME);
        return $this->retrieve_object(History :: get_table_name(), $condition, array(), History :: CLASS_NAME);
    }
}
