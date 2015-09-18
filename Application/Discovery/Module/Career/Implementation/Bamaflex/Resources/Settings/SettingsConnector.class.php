<?php
namespace Ehb\Application\Discovery\Module\Career\Implementation\Bamaflex;

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
                \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: class_name(),
                \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: PROPERTY_TYPE),
            new StaticConditionVariable('Ehb\Application\Discovery\DataSource\Bamaflex'));
        $instances = \Ehb\Application\Discovery\DataSource\Storage\DataManager :: retrieves(
            \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: class_name(),
            new DataClassRetrievesParameters(
                $condition,
                null,
                null,
                array(
                    new OrderBy(
                        new PropertyConditionVariable(
                            \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: class_name(),
                            \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: PROPERTY_NAME)))));

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
