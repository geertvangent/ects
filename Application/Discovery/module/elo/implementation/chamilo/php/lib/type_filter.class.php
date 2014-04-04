<?php
namespace application\discovery\module\elo\implementation\chamilo;

use common\libraries\Translation;
use common\libraries\PropertyConditionVariable;
use common\libraries\EqualityCondition;
use common\libraries\StaticConditionVariable;
use common\libraries\DateFormatConditionVariable;

abstract class TypeDataFilter
{
    const DATE_DAY = 1;
    const DATE_WEEK = 2;
    const DATE_MONTH_YEAR = 3;
    const DATE_YEAR = 4;
    const DATE_HOUR = 5;
    const DATE_WEEKDAY = 6;
    const DATE_MONTH = 7;

    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($type)
    {
        $this->type = $type;
    }

    public static function factory($type)
    {
        $class_name = $type . 'Filter';
        return new $class_name($type);
    }

    public function get_options($filter)
    {
        switch ($filter)
        {
            case TypeData :: PROPERTY_DATE :
                return $this->get_date_options();
                break;

            default :
                return DataManager :: retrieve_filter_options($this->get_type(), $filter);
                break;
        }
    }

    public function get_date_options()
    {
        return array(
            self :: DATE_DAY => Translation :: get('Day'),
            self :: DATE_WEEK => Translation :: get('Week'),
            self :: DATE_MONTH_YEAR => Translation :: get('MonthYear'),
            self :: DATE_YEAR => Translation :: get('Year'),
            self :: DATE_HOUR => Translation :: get('Hour'),
            self :: DATE_WEEKDAY => Translation :: get('WeekDay'),
            self :: DATE_MONTH => Translation :: get('Month'));
    }

    public function format_filter_option($filter, $value)
    {
        switch ($filter)
        {
            case TypeData :: PROPERTY_DATE :
                return $value;
                break;
            default :
                return $value;
        }
    }

    public function get_filter_property($module_type, $filter, $value)
    {
        switch ($filter)
        {
            case TypeData :: PROPERTY_DATE :
                return $this->get_date_filter_property($module_type, $filter, $value);
                break;
            default :
                return new PropertyConditionVariable($module_type, $filter);
        }
    }

    public function get_date_filter_property($module_type, $filter, $value)
    {
        $property = new PropertyConditionVariable($module_type, $filter);
        switch ($value)
        {
            case self :: DATE_DAY :
                $format = '%Y-%m-%d';
                break;
            case self :: DATE_WEEK :
                $format = '%Y-%u';
                break;
            case self :: DATE_MONTH_YEAR :
                $format = '%Y-%m';
                break;
            case self :: DATE_YEAR :
                $format = '%Y';
                break;
            case self :: DATE_HOUR :
                $format = '%H';
                break;
            case self :: DATE_WEEKDAY :
                $format = '%W';
                break;
            case self :: DATE_MONTH :
                $format = '%M';
                break;
        }
        return new DateFormatConditionVariable($format, $property);
    }

    public function get_filter_condition($module_type, $filter, $value)
    {
        switch ($filter)
        {
            case TypeData :: PROPERTY_DATE :
                return null;
                break;
            default :
                if ($value == - 1)
                {
                    return null;
                }
                else
                {
                    return new EqualityCondition(
                        new PropertyConditionVariable($module_type, $filter),
                        new StaticConditionVariable($value));
                }
        }
    }
}