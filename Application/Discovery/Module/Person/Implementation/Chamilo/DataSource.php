<?php
namespace Ehb\Application\Discovery\Module\Person\Implementation\Chamilo;

class DataSource
{

    /**
     *
     * @return \application\discovery\module\person\implementation\chamilo\Person boolean
     */
    public function retrieve_persons($condition, $offset, $count, $order_by)
    {
        return \Chamilo\Core\User\Storage\DataManager :: getInstance()->retrieve_users(
            $condition,
            $offset,
            $count,
            $order_by);
    }

    public function count_persons($condition)
    {
        return \Chamilo\Core\User\Storage\DataManager :: getInstance()->count_users($condition);
    }
}
