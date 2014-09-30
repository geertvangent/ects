<?php
namespace application\discovery\data_source;

use libraries\DataClassRetrievesParameters;
use libraries\DataClassCountParameters;
use libraries\DataClassTableDataProvider;

class InstanceTableDataProvider extends DataClassTableDataProvider
{

    /*
     * (non-PHPdoc) @see \libraries\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        return DataManager :: retrieves(
            Instance :: class_name(),
            new DataClassRetrievesParameters($condition, $count, $offset, $order_property));
    }

    /*
     * (non-PHPdoc) @see \libraries\TableDataProvider::count_data()
     */
    public function count_data($condition)
    {
        return DataManager :: count(Instance :: class_name(), new DataClassCountParameters($condition));
    }
}
