<?php
namespace application\discovery\data_source;

use libraries\ObjectTableDataProvider;
use libraries\DataClassRetrievesParameters;
use libraries\DataClassCountParameters;

class InstanceBrowserTableDataProvider extends ObjectTableDataProvider
{

    public function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return DataManager :: retrieves(
            Instance :: class_name(), 
            new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property));
    }

    public function get_object_count()
    {
        return DataManager :: count(Instance :: class_name(), new DataClassCountParameters($this->get_condition()));
    }
}
