<?php
namespace application\atlantis\application\right;

use common\libraries\DataClassRetrievesParameters;
use common\libraries\NewObjectTableDataProvider;
use common\libraries\ObjectTableDataProvider;

class RightTableDataProvider extends NewObjectTableDataProvider
{

    function get_objects($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $this->get_order_property($order_property));
        return DataManager :: retrieves(Right :: class_name(), $parameters);
    }

    function get_object_count()
    {
        return DataManager :: count(Right :: class_name(), $this->get_condition());
    }
}
?>