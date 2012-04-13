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

    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        
        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_employment_parameters()))
        {
            Display :: not_allowed();
        }
        
        $html = array();
        if (count($this->get_employments()) > 0)
        {
            $html[] = $this->get_table()->toHTML();
        
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }

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

    function get_table()
    {
        $data = array();
        $has_interruption = false;
        
        foreach ($this->get_employments() as $employment)
        {
            $parts = $this->get_employment_parts($employment->get_id());
            $unique_faculty = $this->get_unique_faculty($parts);
            $unique_department = $this->get_unique_department($parts);
            
            $row = array();
            $row[] = $employment->get_year();
            if (count($parts) == 1)
            {
                $row[] = $parts[0]->get_faculty();
            }
            elseif (! is_array($unique_faculty))
            {
                $row[] = $unique_faculty;
            }
            else
            {
                $row[] = implode(', ', $unique_faculty);
            }
            
            if (count($parts) == 1)
            
            {
                $row[] = $parts[0]->get_department();
            }
            elseif (! is_array($unique_department))
            
            {
                $row[] = $unique_department;
            }
            else
            
            {
                $row[] = implode(', ', $unique_department);
            }
            
            $row[] = $employment->get_assignment() . '%';
            
            if ($employment->get_end_date())
            {
                $end_date = DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $employment->get_end_date());
            }
            else
            {
                $end_date = '';
            }
            $start_date = DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $employment->get_start_date());
            
            $row[] = $start_date;
            $row[] = $end_date;
            $row[] = $employment->get_office_category();
            $row[] = $employment->get_state();
            
            if ($employment->get_fund_id())
            {
                $image = '<img src="' . Theme :: get_image_path() . 'fund/' . $employment->get_fund_id() . '.png" alt="' . Translation :: get($employment->get_fund_string()) . '" title="' . Translation :: get($employment->get_fund_string()) . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image, Translation :: get($employment->get_fund_string()), Translation :: get('Fund'));
            }
            else
            {
                $image = '<img src="' . Theme :: get_image_path() . 'fund/0.png" alt="' . Translation :: get('UnknownFund') . '" title="' . Translation :: get('UnknownFund') . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image, Translation :: get('UnknownFund'), Translation :: get('Fund'));
            }
            $row[] = $employment->get_pay_scale();
            
            $image = '<img src="' . Theme :: get_image_path() . 'active/' . $employment->get_active() . '.png" alt="' . Translation :: get($employment->get_active_string()) . '" title="' . Translation :: get($employment->get_active_string()) . '" />';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol($image, Translation :: get($employment->get_active_string()), Translation :: get('Active'));
            
            if ($employment->get_interruption())
            {
                $has_interruption = true;
                $row[] = $employment->get_interruption();
            }
            
            $data[] = $row;
            if (count($parts) > 1)
            {
                foreach ($parts as $part)
                {
                    $row = array();
                    
                    $row[] = ' ';
                    if (is_array($unique_faculty))
                    {
                        $row[] = '<span class="employment_part">' . $part->get_faculty() . '</span>';
                    }
                    else
                    {
                        $row[] = ' ';
                    }
                    
                    if (is_array($unique_department))
                    {
                        $row[] = '<span class="employment_part">' . $part->get_department() . '</span>';
                    }
                    else
                    {
                        $row[] = ' ';
                    }
                    
                    $row[] = '<span class="employment_part">' . $part->get_volume() . '%' . '</span>';
                    $row[] = '<span class="employment_part">' . DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $part->get_start_date()) . '</span>';
                    
                    if ($part->get_end_date())
                    {
                        $row[] = '<span class="employment_part">' . DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $part->get_end_date()) . '</span>';
                    }
                    else
                    {
                        $row[] = '';
                    }
                    $data[] = $row;
                }
            }
        
        }
        
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Year'), false);
        $table->set_header(1, Translation :: get('Faculty'), false);
        $table->set_header(2, Translation :: get('Department'), false);
        $table->set_header(3, Translation :: get('Assignment'), false);
        $table->set_header(4, Translation :: get('StartDate'), false);
        $table->set_header(5, Translation :: get('EndDate'), false);
        $table->set_header(6, Translation :: get('Category'), false);
        $table->set_header(7, Translation :: get('State'), false);
        $table->set_header(8, Translation :: get('Fund'), false);
        $table->set_header(9, Translation :: get('PayScale'), false);
        $table->set_header(10, '', false);
        
        if ($has_interruption)
        {
            $table->set_header(11, Translation :: get('Interruption'), false);
        }
        
        return $table;
    }

    function get_employment_parts($employment_id)
    {
        return DataManager :: get_instance($this->get_module_instance())->retrieve_employment_parts($employment_id);
    }
}
?>