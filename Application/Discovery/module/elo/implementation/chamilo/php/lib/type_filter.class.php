<?php
namespace application\discovery\module\elo\implementation\chamilo;

use common\libraries\Translation;
use application\discovery\module\elo\DataManager;

class TypeDataFilter
{
    const DATE_DAY = 1;
    const DATE_WEEK = 2;
    const DATE_MONTH_YEAR = 3;
    const DATE_YEAR = 4;
    const DATE_HOUR = 5;
    const DATE_WEEKDAY = 6;
    const DATE_MONTH = 7;

    private $rendition_implementation;
    private $type;

    public function __construct($type, $rendition_implementation)
    {
        $this->rendition_implementation = $rendition_implementation;
    }

    public function get_rendition_implementation()
    {
        return $this->rendition_implementation;
    }

    public function set_rendition_implementation($rendition_implementation)
    {
        $this->rendition_implementation = $rendition_implementation;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($type)
    {
        $this->type = $type;
    }

    public static function factory($type, $rendition_implementation)
    {
        $class_name = $type . 'Filter';
        return new $class_name($type, $rendition_implementation);
    }

    public function get_options($filter)
    {
        switch ($filter)
        {
            case TypeData :: PROPERTY_DATE :
                return $this->get_date_options();
                break;

            default :
                return DataManager :: get_instance($this->rendition_implementation->get_module())->retrieve_filter_options(
                    $this->get_type(),
                    $filter);
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
}