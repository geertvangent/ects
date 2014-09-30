<?php
namespace application\atlantis\application;

use libraries\DataClassRetrievesParameters;
use libraries\TableDataProvider;
use libraries\DataClassCountParameters;

class ApplicationTableDataProvider extends TableDataProvider
{

    /*
     * (non-PHPdoc) @see \libraries\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager :: retrieves(Application :: class_name(), $parameters);
    }

    /*
     * (non-PHPdoc) @see \libraries\TableDataProvider::count_data()
     */
    public function count_data($condition)
    {
        return DataManager :: count(Application :: class_name(), new DataClassCountParameters($condition));
    }
}
