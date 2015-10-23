<?php
namespace Ehb\Application\Atlantis\UserGroup\Table\Application;

use Chamilo\Libraries\Format\Table\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Ehb\Application\Atlantis\UserGroup\Storage\DataManager;

class ApplicationTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager :: retrieves(
            \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(), 
            $parameters);
    }

    public function count_data($condition)
    {
        return DataManager :: count(
            \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(), 
            new DataClassCountParameters($condition));
    }
}
