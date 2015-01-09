<?php
namespace Chamilo\Application\Atlantis\rights\table\entity;

use libraries\storage\DataClassCountParameters;
use libraries\storage\DataClassRetrievesParameters;
use libraries\format\TableDataProvider;

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
