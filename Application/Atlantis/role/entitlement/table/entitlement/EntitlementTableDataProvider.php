<?php
namespace Chamilo\Application\Atlantis\role\entitlement\table\entitlement;

use libraries\storage\DataClassRetrievesParameters;
use libraries\format\TableDataProvider;
use libraries\storage\DataClassCountParameters;

class EntitlementTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager :: retrieves(Entitlement :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return DataManager :: count(Entitlement :: class_name(), new DataClassCountParameters($condition));
    }
}
