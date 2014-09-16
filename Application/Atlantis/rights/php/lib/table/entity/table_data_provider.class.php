<?php
namespace application\atlantis\rights;

use libraries\DataClassCountParameters;
use libraries\DataClassRetrievesParameters;
use libraries\NewObjectTableDataProvider;

class EntityTableDataProvider extends NewObjectTableDataProvider
{

    public function get_objects($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_condition(),
            $count,
            $offset,
            $this->get_order_property($order_property));

        return DataManager :: retrieves(RightsLocationEntityRightGroup :: class_name(), $parameters);
    }

    public function get_object_count()
    {
        $parameters = new DataClassCountParameters($this->get_condition());
        return DataManager :: count(RightsLocationEntityRightGroup :: class_name(), $parameters);
    }
}
