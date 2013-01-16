<?php
namespace application\discovery\module\employment\implementation\bamaflex;

use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\ModuleInstance;
use common\libraries\PropertiesTable;
use common\libraries\StringUtilities;
use common\libraries\Display;
use common\libraries\Path;
use common\libraries\ToolbarItem;
use common\libraries\Toolbar;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\module\employment\Module
{

    function get_unique_faculty($parts)
    {
        $faculties = array();
        foreach ($parts as $part)
        {
            if (! in_array($part->get_faculty(), $faculties))
            {
                $faculties[] = $part->get_faculty();
            }
        }
        if (count($faculties) > 1)
        {
            return $faculties;
        }
        else
        {
            return $faculties[0];
        }
    }

    function get_unique_department($parts)
    {
        $departments = array();
        foreach ($parts as $part)
        {
            if (! in_array($part->get_department(), $departments))
            {
                $departments[] = $part->get_department();
            }
        }
        if (count($departments) > 1)
        {
            return $departments;
        }
        else
        {
            return $departments[0];
        }
    }

    function get_employment_parts($employment_id)
    {
        return DataManager :: get_instance($this->get_module_instance())->retrieve_employment_parts($employment_id);
    }
}
?>