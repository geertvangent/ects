<?php
namespace Ehb\Application\Atlantis\Context\Table\Context;

use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Format\Table\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;

class ContextTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($condition, $count, $offset, $order_property);
        return \Chamilo\Core\Group\storage\DataManager :: retrieves(\Chamilo\Core\Group\Storage\DataClass\Group :: class_name(), $parameters);
    }

    public function count_data($condition)
    {
        return \Chamilo\Core\Group\storage\DataManager :: count(
            \Chamilo\Core\Group\Storage\DataClass\Group :: class_name(),
            new DataClassCountParameters($condition));
    }
}
