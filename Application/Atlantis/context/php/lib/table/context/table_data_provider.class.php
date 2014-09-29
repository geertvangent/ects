<?php
namespace application\atlantis\context;

use libraries\DataClassRetrievesParameters;
use libraries\TableDataProvider;

class ContextTableDataProvider extends TableDataProvider
{

    public function retrieve_data($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters(
            $this->get_condition(),
            $count,
            $offset,
            $this->get_order_property($order_property));
        return \core\group\DataManager :: retrieves(\core\group\Group :: class_name(), $parameters);
    }

    public function count_data()
    {
        return \core\group\DataManager :: count(\core\group\Group :: class_name(), $this->get_condition());
    }
}
