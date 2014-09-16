<?php
namespace application\discovery\module\elo\implementation\chamilo;

use libraries\DataClassProperties;
use libraries\ObjectTableOrder;
use libraries\RecordRetrievesParameters;
use libraries\FunctionConditionVariable;
use libraries\PropertyConditionVariable;
use libraries\StaticConditionVariable;
use libraries\GroupBy;
use libraries\AndCondition;
use libraries\Condition;

class DataManager extends \libraries\DataManager
{
    const PREFIX = 'discovery_elo_';
    const COUNT = 'count';

    public static function get_type()
    {
        return 'doctrine';
    }

    public static function retrieve_filter_options($type, $filter)
    {
        $properties = new DataClassProperties();
        $properties->add(
            new FunctionConditionVariable(
                FunctionConditionVariable :: DISTINCT,
                new PropertyConditionVariable($type, $filter),
                $filter));
        $order_by = array(new ObjectTableOrder($filter));
        $parameters = new RecordRetrievesParameters($properties, null, null, null, $order_by);
        $records = self :: records($type, $parameters)->as_array();
        $results = array();

        $results[- 1] = '----';

        foreach ($records as $record)
        {
            $results[$record[$filter]] = TypeDataFilter :: factory($type)->format_filter_option(
                $filter,
                $record[$filter]);
        }
        return $results;
    }

    public static function retrieve_data($module_type, $filter_values)
    {
        $properties = new DataClassProperties();

        $group_by = new GroupBy();
        $conditions = array();
        foreach ($filter_values as $filter => $value)
        {
            $property = TypeDataFilter :: factory($module_type)->get_filter_property($module_type, $filter, $value);

            $properties->add($property);

            $group_by->add($property);

            $filter_condition = TypeDataFilter :: factory($module_type)->get_filter_condition(
                $module_type,
                $filter,
                $value);
            if ($filter_condition instanceof Condition)
            {
                $conditions[] = $filter_condition;
            }
        }

        if (count($conditions))
        {
            $condition = new AndCondition($conditions);
        }
        else
        {
            $condition = null;
        }
        $properties->add(
            new FunctionConditionVariable(
                FunctionConditionVariable :: COUNT,
                new StaticConditionVariable(1),
                self :: COUNT));

        $parameters = new RecordRetrievesParameters($properties, $condition, null, null, null, null, $group_by);

        return self :: records($module_type, $parameters)->as_array();
    }
}