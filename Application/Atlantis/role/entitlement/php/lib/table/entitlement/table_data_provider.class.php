<?php
namespace application\atlantis\role\entitlement;

use common\libraries\DataClassRetrievesParameters;
use common\libraries\NewObjectTableDataProvider;
use common\libraries\ObjectTableDataProvider;

class EntitlementTableDataProvider extends NewObjectTableDataProvider
{

    function get_objects($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $this->get_order_property($order_property));
        return DataManager :: retrieves(Entitlement :: class_name(), $parameters);
    }

    function get_object_count()
    {
        return DataManager :: count(Entitlement :: class_name(), $this->get_condition());
    }
}
?>