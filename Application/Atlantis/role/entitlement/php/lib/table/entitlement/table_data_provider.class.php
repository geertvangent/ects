<?php
namespace application\atlantis\role\entitlement;

use libraries\DataClassRetrievesParameters;
use libraries\TableDataProvider;

class EntitlementTableDataProvider extends TableDataProvider
{

    public function retrieve_data($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_condition(),
            $count,
            $offset,
            $this->get_order_property($order_property));
        return DataManager :: retrieves(Entitlement :: class_name(), $parameters);
    }

    public function count_data()
    {
        return DataManager :: count(Entitlement :: class_name(), $this->get_condition());
    }
}
