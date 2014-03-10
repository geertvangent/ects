<?php
namespace application\atlantis\user_group;

use common\libraries\DataClassRetrievesParameters;
use common\libraries\NewObjectTableDataProvider;

class ApplicationTableDataProvider extends NewObjectTableDataProvider
{

    public function get_objects($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($this->get_condition(), $count, $offset, 
                $this->get_order_property($order_property));
        return DataManager :: retrieves(\application\atlantis\application\Application :: class_name(), $parameters);
    }

    public function get_object_count()
    {
        return DataManager :: count(\application\atlantis\application\Application :: class_name(), 
                $this->get_condition());
    }
}
