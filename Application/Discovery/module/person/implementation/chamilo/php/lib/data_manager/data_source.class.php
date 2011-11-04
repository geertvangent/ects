<?php
namespace application\discovery\module\person\implementation\chamilo;

use application\discovery\module\person\DataManagerInterface;

use user\UserDataManager;

class DataSource implements DataManagerInterface
{

    /**
     * @return \application\discovery\module\person\implementation\chamilo\Person|boolean
     */
    function retrieve_persons($condition, $offset, $count, $order_by)
    {
    	return UserDataManager::get_instance()->retrieve_users($condition, $offset, $count, $order_by);
    }
    
    function count_persons($condition)
    {
    	return UserDataManager::get_instance()->count_users($condition);
    }
}
?>