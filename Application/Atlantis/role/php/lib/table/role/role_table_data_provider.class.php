<?php
namespace application\atlantis\role;

use libraries\DataClassRetrievesParameters;
use libraries\NewObjectTableDataProvider;

class RoleTableDataProvider extends NewObjectTableDataProvider
{

    public function get_objects($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_condition(), 
            $count, 
            $offset, 
            $this->get_order_property($order_property));
        return DataManager :: retrieves(Role :: class_name(), $parameters);
    }

    public function get_object_count()
    {
        return DataManager :: count(Role :: class_name(), $this->get_condition());
    }
}
