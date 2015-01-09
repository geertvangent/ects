<?php
namespace Chamilo\Application\Atlantis\UserGroup\Table\Application;

use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Format\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;

class ApplicationTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager :: retrieves(\Chamilo\Application\Atlantis\Application\Application :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return DataManager :: count(
            \Chamilo\Application\Atlantis\Application\Application :: class_name(),
            new DataClassCountParameters($condition));
    }
}
