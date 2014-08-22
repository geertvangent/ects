<?php
namespace application\atlantis\application;

use libraries\DataClassRetrievesParameters;
use libraries\NewObjectTableDataProvider;

class ApplicationTableDataProvider extends NewObjectTableDataProvider
{

    public function get_objects($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_condition(),
            $count,
            $offset,
            $this->get_order_property($order_property));
        return DataManager :: retrieves(Application :: class_name(), $parameters);
    }

    public function get_object_count()
    {
        return DataManager :: count(Application :: class_name(), $this->get_condition());
    }
}
