<?php
namespace Chamilo\Application\Discovery\DataSource\Table\Instance;

use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableDataProvider;
use Chamilo\Application\Discovery\DataSource\DataManager;
use Chamilo\Application\Discovery\DataSource\DataClass\Instance;

class InstanceTableDataProvider extends DataClassTableDataProvider
{

    /*
     * (non-PHPdoc) @see \libraries\format\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        return DataManager :: retrieves(
            Instance :: class_name(),
            new DataClassRetrievesParameters($condition, $count, $offset, $order_property));
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableDataProvider::count_data()
     */
    public function count_data($condition)
    {
        return DataManager :: count(Instance :: class_name(), new DataClassCountParameters($condition));
    }
}
