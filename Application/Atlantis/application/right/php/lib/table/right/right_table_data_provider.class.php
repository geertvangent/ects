<?php
namespace application\atlantis\application\right;

use libraries\DataClassRetrievesParameters;
use libraries\TableDataProvider;
use libraries\DataClassCountParameters;

class RightTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager :: retrieves(Right :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return DataManager :: count(Right :: class_name(), new DataClassCountParameters($condition()));
    }
}
