<?php
namespace Chamilo\Application\Atlantis\Role\Table\Role;

use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Format\Table\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Application\Atlantis\Role\DataManager;
use Chamilo\Application\Atlantis\Role\DataClass\Role;

class RoleTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager :: retrieves(Role :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return DataManager :: count(Role :: class_name(), new DataClassCountParameters($condition));
    }
}
