<?php
namespace Chamilo\Application\Discovery\Instance\Table\Instance;

use Chamilo\Libraries\Storage\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\DataClassCountParameters;
use Chamilo\Libraries\Format\DataClassTableDataProvider;

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
