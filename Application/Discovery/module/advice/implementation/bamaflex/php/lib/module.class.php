<?php
namespace application\discovery\module\advice\implementation\bamaflex;

use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;

use common\libraries\Display;

use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\ResourceManager;
use common\libraries\Path;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\enrollment\DataManager;

class Module extends \application\discovery\module\advice\Module
{
    private $cache_advices = array();

    function get_advices_data($enrollment)
    {
        if (! isset($this->cache_advices[$enrollment->get_id()]))
        {
            $advices = array();
            foreach ($this->get_advices() as $advice)
            {
                if ($advice->get_enrollment_id() == $enrollment->get_id())
                {
                    $advices[] = $advice;
                }
            }
            
            $this->cache_advices[$enrollment->get_id()] = $advices;
        }
        return $this->cache_advices[$enrollment->get_id()];
    }

    function has_advices($enrollment = null)
    {
        if ($enrollment)
        {
            return count($this->get_advices_data($enrollment));
        }
        else
        {
            return count($this->get_advices()) > 0;
        }
    }

    function get_advices_table($enrollment)
    {
        $advices = $this->get_advices_data($enrollment);
        
        $data = array();
        
        foreach ($advices as $key => $advice)
        {
            
            if ($advice->get_motivation())
            {
                $row = array();
                
                $image = '<img src="' . Theme :: get_image_path() . 'type/' . Advice :: TYPE_MOTIVATION . '.png" alt="' . Translation :: get(Advice :: type_string(Advice :: TYPE_MOTIVATION)) . '" title="' . Translation :: get(Advice :: type_string(Advice :: TYPE_MOTIVATION)) . '"/>';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image, Translation :: get(Advice :: type_string(Advice :: TYPE_MOTIVATION)), Translation :: get('Type'));
                
                $row[] = $advice->get_try();
                $row[] = DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $advice->get_date());
                $row[] = $advice->get_decision_type();
                
                $row[] = $advice->get_motivation();
                
                $data[] = $row;
            }
            
            if ($advice->get_ombudsman())
            {
                $row = array();
                
                $image = '<img src="' . Theme :: get_image_path() . 'type/' . Advice :: TYPE_OMBUDSMAN . '.png" alt="' . Translation :: get(Advice :: type_string(Advice :: TYPE_OMBUDSMAN)) . '" title="' . Translation :: get(Advice :: type_string(Advice :: TYPE_OMBUDSMAN)) . '"/>';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image, Translation :: get(Advice :: type_string(Advice :: TYPE_OMBUDSMAN)), Translation :: get('Type'));
                
                $row[] = $advice->get_try();
                $row[] = DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $advice->get_date());
                $row[] = $advice->get_decision_type();
                $row[] = $advice->get_ombudsman();
                
                $data[] = $row;
            }
            
            if ($advice->get_vote())
            {
                $row = array();
                
                $image = '<img src="' . Theme :: get_image_path() . 'type/' . Advice :: TYPE_VOTE . '.png" alt="' . Translation :: get(Advice :: type_string(Advice :: TYPE_VOTE)) . '" title="' . Translation :: get(Advice :: type_string(Advice :: TYPE_VOTE)) . '"/>';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image, Translation :: get(Advice :: type_string(Advice :: TYPE_VOTE)), Translation :: get('Type'));
                
                $row[] = $advice->get_try();
                $row[] = DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $advice->get_date());
                $row[] = $advice->get_decision_type();
                $row[] = $advice->get_vote();
                
                $data[] = $row;
            }
            
            if ($advice->get_measures() /*&& ($advice->get_measures_visible())*/)
            {
                $row = array();
                if ($advice->get_measures_valid())
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'type/' . Advice :: TYPE_MEASURES_VALID . '.png" alt="' . Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_VALID)) . '" title="' . Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_VALID)) . '"/>';
                    $row[] = $image;
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_VALID)), Translation :: get('Type'));
                }
                else
                {
                    $image = '<img src="' . Theme :: get_image_path() . 'type/' . Advice :: TYPE_MEASURES_INVALID . '.png" alt="' . Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_INVALID)) . '" title="' . Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_INVALID)) . '"/>';
                    $row[] = $image;
                    LegendTable :: get_instance()->add_symbol($image, Translation :: get(Advice :: type_string(Advice :: TYPE_MEASURES_INVALID)), Translation :: get('Type'));
                }
                $row[] = $advice->get_try();
                $row[] = DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $advice->get_date());
                $row[] = $advice->get_decision_type();
                $row[] = $advice->get_measures();
                
                $data[] = $row;
            }
            
            if ($advice->get_advice()/* && ($advice->get_advice_visible())*/)
            {
                $row = array();
                
                $image = '<img src="' . Theme :: get_image_path() . 'type/' . Advice :: TYPE_ADVICE . '.png" alt="' . Translation :: get(Advice :: type_string(Advice :: TYPE_ADVICE)) . '" title="' . Translation :: get(Advice :: type_string(Advice :: TYPE_ADVICE)) . '"/>';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image, Translation :: get(Advice :: type_string(Advice :: TYPE_ADVICE)), Translation :: get('Type'));
                
                $row[] = $advice->get_try();
                $row[] = DatetimeUtilities :: format_locale_date(Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES), $advice->get_date());
                $row[] = $advice->get_decision_type();
                $row[] = $advice->get_advice();
                
                $data[] = $row;
            }
        }
        
        $table = new SortableTable($data);
        
        $table->set_header(0, Translation :: get('Type'), false);
        $table->getHeader()->setColAttributes(0, 'class="action"');
        
        $table->set_header(1, Translation :: get('Try'), false);
        $table->set_header(2, Translation :: get('Date'), false);
        
        $table->set_header(3, Translation :: get('DecisionType'), false);
        $table->set_header(4, Translation :: get('Advice'), false);

        
        return $table;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\advice\Module::render()
     */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        
        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_advice_parameters()))
        {
            Display :: not_allowed();
        }
        
        $html = array();
        
        if ($this->has_advices())
        {
            
            $enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments($this->get_advice_parameters());
            foreach ($enrollments as $enrollment)
            {
                if ($this->has_advices($enrollment))
                {
                    $html[] = '<table class="data_table" id="tablename"><thead><tr><th class="action">';
                    
                    if ($enrollment->is_special_result())
                    {
                        $tab_image_path = Theme :: get_image_path(Utilities :: get_namespace_from_classname(Enrollment :: CLASS_NAME)) . 'result_type/' . $enrollment->get_result() . '.png';
                        $tab_image = '<img src="' . $tab_image_path . '" alt="' . Translation :: get($enrollment->get_result_string()) . '" title="' . Translation :: get($enrollment->get_result_string()) . '" />';
                        $html[] = $tab_image;
                        LegendTable :: get_instance()->add_symbol($tab_image, Translation :: get($enrollment->get_result_string()), Translation :: get('ResultType'));
                    }
                    
                    $html[] = '</th><th class="action">';
                    $tab_image_path = Theme :: get_image_path(Utilities :: get_namespace_from_classname(Enrollment :: CLASS_NAME)) . 'contract_type/' . $enrollment->get_contract_type() . '.png';
                    $tab_image = '<img src="' . $tab_image_path . '" alt="' . Translation :: get($enrollment->get_contract_type_string()) . '" title="' . Translation :: get($enrollment->get_contract_type_string()) . '" />';
                    $html[] = $tab_image;
                    LegendTable :: get_instance()->add_symbol($tab_image, Translation :: get($enrollment->get_contract_type_string()), Translation :: get('ContractType'));
                    
                    $html[] = '</th><th>';
                    
                    $enrollment_name = array();
                    
                    $enrollment_name[] = $enrollment->get_year();
                    $enrollment_name[] = $enrollment->get_training();
                    
                    if ($enrollment->get_unified_option())
                    {
                        $enrollment_name[] = $enrollment->get_unified_option();
                    }
                    
                    if ($enrollment->get_unified_trajectory())
                    {
                        $enrollment_name[] = $enrollment->get_unified_trajectory();
                    }
                    
                    $html[] = implode(' | ', $enrollment_name);
                    $html[] = '</th></tr></thead></table>';
                    $html[] = '<br />';
                    $html[] = $this->get_advices_table($enrollment)->toHTML();
                    $html[] = '<br />';
                }
            }
        
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }
}
?>