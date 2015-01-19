<?php
namespace Chamilo\Application\Atlantis\Role\Entitlement\Table\Entitlement;

use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Format\Table\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Application\Atlantis\Role\Entitlement\Storage\DataManager;
use Chamilo\Application\Atlantis\Role\Entitlement\Storage\DataClass\Entitlement;

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
