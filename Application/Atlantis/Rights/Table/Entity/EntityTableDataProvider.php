<?php
namespace Ehb\Application\Atlantis\Rights\Table\Entity;

use Chamilo\Libraries\Format\Table\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Ehb\Application\Atlantis\Rights\Storage\DataClass\RightsLocationEntityRightGroup;
use Ehb\Application\Atlantis\Rights\Storage\DataManager;

class EntityTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        
        return DataManager :: retrieves(RightsLocationEntityRightGroup :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return DataManager :: count(
            RightsLocationEntityRightGroup :: class_name(), 
            new DataClassCountParameters($condition));
    }
}
