<?php
namespace Ehb\Application\Atlantis\Role\Entity\Table\RoleEntity;

use Chamilo\Libraries\Format\Table\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity;
use Ehb\Application\Atlantis\Role\Entity\Storage\DataManager;

class RoleEntityTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager::retrieves(RoleEntity::class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return DataManager::count(RoleEntity::class_name(), new DataClassCountParameters($condition));
    }
}
