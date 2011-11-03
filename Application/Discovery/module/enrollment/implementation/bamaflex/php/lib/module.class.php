<?php
namespace application\discovery\module\enrollment\implementation\bamaflex;

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

class Module extends \application\discovery\module\enrollment\Module
{

    function get_enrollments_table($contract_type = Enrollment :: CONTRACT_TYPE_ALL)
    {
        if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
        {
            $enrollments = $this->get_enrollments();
        }
        else
        {
            $enrollments = array();
            foreach ($this->get_enrollments() as $enrollment)
            {
                if ($enrollment->get_contract_type() == $contract_type)
                {
                    $enrollments[] = $enrollment;
                }
            }
        }
        
        $data = array();
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $training_module_instance = \application\discovery\Module :: exists('application\discovery\module\training\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        $training_info_module_instance = \application\discovery\Module :: exists('application\discovery\module\training_info\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        foreach ($enrollments as $key => $enrollment)
        {
            $row = array();
            $row[] = $enrollment->get_year();
            
            if ($training_module_instance)
            {
                $parameters = new \application\discovery\module\training\implementation\bamaflex\Parameters($enrollment->get_faculty_id(), $enrollment->get_source());
                $url = $this->get_instance_url($training_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $enrollment->get_faculty() . '</a>';
            }
            else
            {
                $row[] = $enrollment->get_faculty();
            }
            
            if ($training_info_module_instance)
            {
                $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters($enrollment->get_training_id(), $enrollment->get_source());
                $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $enrollment->get_training() . '</a>';
            }
            else
            {
                $row[] = $enrollment->get_training();
            }
            
            $row[] = $enrollment->get_unified_option();
            $row[] = $enrollment->get_unified_trajectory();
            
            if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
            {
                $row[] = Translation :: get($enrollment->get_contract_type_string());
            }
            
            if ($enrollment->is_special_result())
            {
                $image = '<img src="' . Theme :: get_image_path() . 'result_type/' . $enrollment->get_result() . '.png" alt="' . Translation :: get($enrollment->get_result_string()) . '" title="' . Translation :: get($enrollment->get_result_string()) . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image, Translation :: get($enrollment->get_result_string()), Translation :: get('ResultType'));
            }
            else
            {
                $row[] = ' ';
            }
            
            //            $class = 'enrollment" style="" id="enrollment_' . $key;
            //            $details_action = new ToolbarItem(Translation :: get('ShowCourses'), Theme :: get_common_image_path() . 'action_details.png', '#', ToolbarItem :: DISPLAY_ICON, false, $class);
            //            $row[] = $details_action->as_html();
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Year'), false);
        $table->set_header(1, Translation :: get('Faculty'), false);
        $table->set_header(2, Translation :: get('Training'), false);
        $table->set_header(3, Translation :: get('Option'), false);
        $table->set_header(4, Translation :: get('Trajectory'), false);
        if ($contract_type == Enrollment :: CONTRACT_TYPE_ALL)
        {
            $table->set_header(5, Translation :: get('Contract'), false);
            $table->set_header(6, '', false);
        }
        else
        {
            $table->set_header(5, '', false);
        }
        
        return $table;
    }

    /* (non-PHPdoc)
     * @see application\discovery\module\enrollment.Module::render()
     */
    function render()
    {
        $html = array();
        if (count($this->get_enrollments()) > 0)
        {
            $contract_types = DataManager :: get_instance($this->get_module_instance())->retrieve_contract_types($this->get_enrollment_parameters());
            
            $tabs = new DynamicTabsRenderer('enrollment_list');
            $tabs->add_tab(new DynamicContentTab(Enrollment :: CONTRACT_TYPE_ALL, Translation :: get('AllContracts'), Theme :: get_image_path() . 'contract_type/0.png', $this->get_enrollments_table(Enrollment :: CONTRACT_TYPE_ALL)->toHTML()));
            
            foreach ($contract_types as $contract_type)
            {
                $tabs->add_tab(new DynamicContentTab($contract_type, Translation :: get(Enrollment :: contract_type_string($contract_type)), Theme :: get_image_path() . 'contract_type/' . $contract_type . '.png', $this->get_enrollments_table($contract_type)->toHTML()));
            }
            
            $html[] = $tabs->render();
        }
        else
        {
            $html[] = Display:: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }
}
?>