<?php
namespace application\discovery\data_source\bamaflex;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
use common\libraries\InCondition;
use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\Mdb2Database;

class Mdb2DiscoveryDataManager extends \application\discovery\Mdb2DiscoveryDataManager
{

    function initialize()
    {
        parent :: initialize();
        $this->set_prefix('discovery_bamaflex_');
    }

    function retrieve_history_by_conditions($condition)
    {
        return $this->retrieve_objects(History :: get_table_name(), $condition, null, null, array(), History :: CLASS_NAME);
        return $this->retrieve_object(History :: get_table_name(), $condition, array(), History :: CLASS_NAME);
    }
}
?>