<?php
namespace application\atlantis\role\entity;

use libraries\DataClassRetrievesParameters;
use libraries\TableDataProvider;
use libraries\DataClassCountParameters;

class RoleEntityTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager :: retrieves(RoleEntity :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return DataManager :: count(RoleEntity :: class_name(), new DataClassCountParameters($condition));
    }
}
