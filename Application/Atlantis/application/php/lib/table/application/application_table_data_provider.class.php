<?php
namespace application\atlantis\application;

use libraries\DataClassRetrievesParameters;
use libraries\TableDataProvider;

class ApplicationTableDataProvider extends TableDataProvider
{

    /*
     * (non-PHPdoc) @see \libraries\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_condition(),
            $count,
            $offset,
            $this->get_order_property($order_property));
        return DataManager :: retrieves(Application :: class_name(), $parameters);
    }

    /*
     * (non-PHPdoc) @see \libraries\TableDataProvider::count_data()
     */
    public function count_data()
    {
        return DataManager :: count(Application :: class_name(), $this->get_condition());
    }
}
