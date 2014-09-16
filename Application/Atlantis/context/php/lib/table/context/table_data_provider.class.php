<?php
namespace application\atlantis\context;

use libraries\DataClassRetrievesParameters;
use libraries\NewObjectTableDataProvider;

class ContextTableDataProvider extends NewObjectTableDataProvider
{

    public function get_objects($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_condition(),
            $count,
            $offset,
            $this->get_order_property($order_property));
        return \core\group\DataManager :: retrieves(\core\group\Group :: class_name(), $parameters);
    }

    public function get_object_count()
    {
        return \core\group\DataManager :: count(\core\group\Group :: class_name(), $this->get_condition());
    }
}
