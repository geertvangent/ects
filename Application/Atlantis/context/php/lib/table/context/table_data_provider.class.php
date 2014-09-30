<?php
namespace application\atlantis\context;

use libraries\DataClassRetrievesParameters;
use libraries\TableDataProvider;
use libraries\DataClassCountParameters;

class ContextTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return \core\group\DataManager :: retrieves(\core\group\Group :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return \core\group\DataManager :: count(
            \core\group\Group :: class_name(),
            new DataClassCountParameters($condition));
    }
}
