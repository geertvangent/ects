<?php
namespace Application\Discovery\module\person\implementation\chamilo\data_manager;

class DataSource
{

    /**
     *
     * @return \application\discovery\module\person\implementation\chamilo\Person boolean
     */
    public function retrieve_persons($condition, $offset, $count, $order_by)
    {
        return \core\user\DataManager :: get_instance()->retrieve_users($condition, $offset, $count, $order_by);
    }

    public function count_persons($condition)
    {
        return \core\user\DataManager :: get_instance()->count_users($condition);
    }
}
