<?php
namespace application\atlantis\user_group;

use libraries\DataClassRetrievesParameters;
use libraries\TableDataProvider;

class ApplicationTableDataProvider extends TableDataProvider
{

    public function retrieve_data($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_condition(),
            $count,
            $offset,
            $this->get_order_property($order_property));
        return DataManager :: retrieves(\application\atlantis\application\Application :: class_name(), $parameters);
    }

    public function count_data()
    {
        return DataManager :: count(
            \application\atlantis\application\Application :: class_name(),
            $this->get_condition());
    }
}
