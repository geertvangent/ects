<?php
namespace Chamilo\Application\Atlantis\Role\Entity\Table\RoleEntity;

use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Format\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;

class RoleEntityTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager :: retrieves(RoleEntity :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return DataManager :: count(RoleEntity :: class_name(), new DataClassCountParameters($condition));
    }
}
