<?php
namespace application\atlantis\rights;

use libraries\DataClassCountParameters;
use libraries\DataClassRetrievesParameters;
use libraries\TableDataProvider;

class EntityTableDataProvider extends TableDataProvider
{

    public function retrieve_data($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_condition(),
            $count,
            $offset,
            $this->get_order_property($order_property));

        return DataManager :: retrieves(RightsLocationEntityRightGroup :: class_name(), $parameters);
    }

    public function count_data()
    {
        $parameters = new DataClassCountParameters($this->get_condition());
        return DataManager :: count(RightsLocationEntityRightGroup :: class_name(), $parameters);
    }
}
