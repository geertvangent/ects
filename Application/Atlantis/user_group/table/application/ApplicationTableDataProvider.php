<?php
namespace Chamilo\Application\Atlantis\user_group\table\application;

use libraries\storage\DataClassRetrievesParameters;
use libraries\format\TableDataProvider;
use libraries\storage\DataClassCountParameters;

class ApplicationTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return DataManager :: retrieves(\application\atlantis\application\Application :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return DataManager :: count(
            \application\atlantis\application\Application :: class_name(),
            new DataClassCountParameters($condition));
    }
}
