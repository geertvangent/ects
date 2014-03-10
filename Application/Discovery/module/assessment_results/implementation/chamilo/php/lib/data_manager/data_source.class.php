<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

use application\discovery\module\assessment_results\DataManagerInterface;
use user\UserDataManager;

class DataSource implements DataManagerInterface
{

    /**
     *
     * @return \application\discovery\module\assessment_results\implementation\chamilo\Person boolean
     */
    public function retrieve_persons($condition, $offset, $count, $order_by)
    {
        return UserDataManager :: get_instance()->retrieve_users($condition, $offset, $count, $order_by);
    }

    public function count_persons($condition)
    {
        return UserDataManager :: get_instance()->count_users($condition);
    }
}
