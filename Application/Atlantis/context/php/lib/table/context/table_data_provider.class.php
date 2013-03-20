<?php
namespace application\atlantis\context;

use common\libraries\DataClassRetrievesParameters;
use common\libraries\NewObjectTableDataProvider;

class ContextTableDataProvider extends NewObjectTableDataProvider
{

    public function get_objects($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_condition(), 
            $count, 
            $offset, 
            $this->get_order_property($order_property));
        return DataManager :: retrieves(Context :: class_name(), $parameters);
    }

    public function get_object_count()
    {
        return DataManager :: count(Context :: class_name(), $this->get_condition());
    }
}
