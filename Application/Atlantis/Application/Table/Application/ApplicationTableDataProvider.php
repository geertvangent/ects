<?php
namespace Ehb\Application\Atlantis\Application\Table\Application;

use Chamilo\Libraries\Format\Table\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Ehb\Application\Atlantis\Application\Storage\DataClass\Application;
use Ehb\Application\Atlantis\Application\Storage\DataManager;

class ApplicationTableDataProvider extends TableDataProvider
{

    /*
     * (non-PHPdoc) @see \libraries\format\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager::retrieves(Application::class_name(), $parameters);
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableDataProvider::count_data()
     */
    public function count_data($condition)
    {
        return DataManager::count(Application::class_name(), new DataClassCountParameters($condition));
    }
}
