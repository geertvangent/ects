<?php
namespace application\discovery\data_source;

use libraries\TableDataProvider;
use libraries\DataClassRetrievesParameters;
use libraries\DataClassCountParameters;

class InstanceBrowserTableDataProvider extends TableDataProvider
{

    /*
     * (non-PHPdoc) @see \libraries\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return DataManager :: retrieves(
            Instance :: class_name(),
            new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property));
    }

    /*
     * (non-PHPdoc) @see \libraries\TableDataProvider::count_data()
     */
    public function count_data($condition)
    {
        return DataManager :: count(Instance :: class_name(), new DataClassCountParameters($this->get_condition()));
    }
}
