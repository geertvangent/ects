<?php
namespace Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex;

use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

class SettingsConnector
{

    public static function get_data_sources()
    {
        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                \Chamilo\Application\Discovery\DataSource\DataClass\Instance :: class_name(),
                \Chamilo\Application\Discovery\DataSource\DataClass\Instance :: PROPERTY_TYPE),
            new StaticConditionVariable('application\discovery\data_source\bamaflex'));
        $instances = \Chamilo\Application\Discovery\DataSource\DataManager :: retrieves(
            \Chamilo\Application\Discovery\DataSource\DataClass\Instance :: class_name(),
            new DataClassRetrievesParameters(
                $condition,
                null,
                null,
                array(
                    new OrderBy(
                        new PropertyConditionVariable(
                            \Chamilo\Application\Discovery\DataSource\DataClass\Instance :: class_name(),
                            \Chamilo\Application\Discovery\DataSource\DataClass\Instance :: PROPERTY_NAME)))));

        $data_sources = array();

        if ($instances->size() == 0)
        {
            $data_sources[0] = Translation :: get('AddConnectionInstanceFirst');
        }
        else
        {
            while ($instance = $instances->next_result())
            {
                $data_sources[$instance->get_id()] = $instance->get_name();
            }
        }

        return $data_sources;
    }
}
