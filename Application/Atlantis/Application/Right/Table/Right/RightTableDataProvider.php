<?php
namespace Chamilo\Application\Atlantis\Application\Right\Table\Right;

use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Format\Table\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Application\Atlantis\Application\Right\Table\DataManager;
use Chamilo\Application\Atlantis\Application\Right\Table\DataClass\Right;

class RightTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager :: retrieves(Right :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return DataManager :: count(Right :: class_name(), new DataClassCountParameters($condition()));
    }
}
