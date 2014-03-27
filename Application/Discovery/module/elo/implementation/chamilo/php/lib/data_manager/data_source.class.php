<?php
namespace application\discovery\module\elo\implementation\chamilo;

use application\discovery\module\elo\DataManagerInterface;
use application\atlantis\DataManager;
use common\libraries\RecordRetrievesParameters;
use common\libraries\DataClassProperties;
use common\libraries\DataClassProperty;
use common\libraries\FunctionConditionVariable;
use common\libraries\PropertyConditionVariable;
use common\libraries\ObjectTableOrder;

class DataSource implements DataManagerInterface
{

    public function retrieve_filter_options($type, $filter)
    {
        $properties = new DataClassProperties();
        $properties->add(
            new DataClassProperty(
                new FunctionConditionVariable(
                    FunctionConditionVariable :: DISTINCT,
                    new PropertyConditionVariable($type, $filter)),
                $filter));
        $order_by = array(new ObjectTableOrder($filter));
        $parameters = new RecordRetrievesParameters($properties, null, null, null, $order_by);
        return DataManager :: records($type, $parameters);
    }
}
