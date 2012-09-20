<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

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

class Module extends \application\discovery\module\teaching_assignment\Module
{
    private $cache_teaching_assignments = array();

    function get_teaching_assignments_data($parameters)
    {
        $year = $parameters->get_year();
        $user_id = $parameters->get_user_id();
        
        if (! isset($this->cache_teaching_assignments[$user_id][$year]))
        {
            foreach ($this->get_teaching_assignments($parameters) as $teaching_assignment)
            {
                $this->cache_teaching_assignments[$user_id][$year][] = $teaching_assignment;
            }
        }
        return $this->cache_teaching_assignments[$user_id][$year];
    }

    function has_teaching_assignments($parameters)
    {
        return count($this->get_teaching_assignments_data($parameters)) > 0;
    }

    function get_teaching_assignments_table($parameters)
    {
        $teaching_assignments = $this->get_teaching_assignments_data($parameters);
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $course_module_instance = \application\discovery\Module :: exists('application\discovery\module\course\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        $course_result_module_instance = \application\discovery\Module :: exists('application\discovery\module\course_results\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        $faculty_info_module_instance = \application\discovery\Module :: exists('application\discovery\module\faculty_info\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        $training_info_module_instance = \application\discovery\Module :: exists('application\discovery\module\training_info\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        foreach ($teaching_assignments as $key => $teaching_assignment)
        {
            $row = array();
            
            if ($faculty_info_module_instance)
            {
                $parameters = new \application\discovery\module\faculty_info\implementation\bamaflex\Parameters($teaching_assignment->get_faculty_id(), $teaching_assignment->get_source());
                $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_faculty() . '</a>';
            }
            else
            {
                $row[] = $teaching_assignment->get_faculty();
            }
            
            if ($training_info_module_instance)
            {
                $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters($teaching_assignment->get_training_id(), $teaching_assignment->get_source());
                $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_training() . '</a>';
            }
            else
            {
                $row[] = $teaching_assignment->get_training();
            }
            
            $image = '<img src="' . Theme :: get_image_path() . 'type/' . $teaching_assignment->get_manager() . '.png" alt="' . Translation :: get($teaching_assignment->get_manager_type()) . '" title="' . Translation :: get($teaching_assignment->get_manager_type()) . '"/>';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol($image, Translation :: get($teaching_assignment->get_manager_type()), Translation :: get('Manager'));
            
            $image = '<img src="' . Theme :: get_image_path() . 'type/' . $teaching_assignment->get_teacher() . '.png" alt="' . Translation :: get($teaching_assignment->get_teacher_type()) . '" title="' . Translation :: get($teaching_assignment->get_teacher_type()) . '"/>';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol($image, Translation :: get($teaching_assignment->get_teacher_type()), Translation :: get('Teacher'));
            
            if ($course_module_instance)
            {
                $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters($teaching_assignment->get_programme_id(), $teaching_assignment->get_source());
                $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_name() . '</a>';
            }
            else
            {
                $row[] = $teaching_assignment->get_name();
            }
            $row[] = $teaching_assignment->get_credits();
            $image = '<img src="' . Theme :: get_image_path() . 'timeframe/' . $teaching_assignment->get_timeframe_id() . '.png" alt="' . Translation :: get($teaching_assignment->get_timeframe()) . '" title="' . Translation :: get($teaching_assignment->get_timeframe()) . '"/>';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol($image, Translation :: get($teaching_assignment->get_timeframe()), Translation :: get('Timeframe'));
            
            if ($course_result_module_instance)
            {
                $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters($teaching_assignment->get_programme_id(), $teaching_assignment->get_source());
                $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                $row[] = Theme :: get_common_image('action_details', 'png', Translation :: get('CourseResults'), $url, ToolbarItem :: DISPLAY_ICON);
            }
            
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        
        $table->set_header(0, Translation :: get('Faculty'), false);
        $table->set_header(1, Translation :: get('Training'), false);
        $table->set_header(2, '<img src="' . Theme :: get_image_path() . 'manager.png"/>', false);
        $table->set_header(3, '<img src="' . Theme :: get_image_path() . 'teacher.png"/>', false);
        $table->set_header(4, Translation :: get('Name'), false);
        $table->set_header(5, Translation :: get('Credits'), false, 'class="action"');
        $table->set_header(6, '<img src="' . Theme :: get_image_path() . 'timeframe.png"/>', false);
        $table->set_header(7, '', false);
        
        return $table;
    }

    function get_years()
    {
        if (! isset($this->years))
        {
            $this->years = DataManager :: get_instance($this->get_module_instance())->retrieve_years($this->get_teaching_assignment_parameters());
        }
        return $this->years;
    
    }
    
    /* (non-PHPdoc)
     * @see application\discovery\module\teaching_assignment\Module::render()
     */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        
        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_teaching_assignment_parameters()))
        {
            Display :: not_allowed();
        }
        
        if (is_null(self :: get_module_parameters()->get_year()))
        {
            $years = $this->get_years();
            $current_year = $years[0];
        }
        else
        {
            $current_year = self :: get_module_parameters()->get_year();
        }
        $parameters = $this->get_teaching_assignment_parameters();
        $parameters->set_year($current_year);
        
        $html = array();
        
        if ($this->has_data())
        {
            $tabs = new DynamicVisualTabsRenderer('teaching_assignment_list', $this->get_teaching_assignments_table($parameters)->toHTML());
            
            foreach ($this->get_years() as $year)
            {
                $parameters = self :: get_module_parameters();
                $parameters->set_year($year);
                $tabs->add_tab(new DynamicVisualTab($year, $year, null, $this->get_instance_url($this->get_module_instance()->get_id(), $parameters), $current_year == $year));
            }
            
            $html[] = $tabs->render();
        
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        return implode("\n", $html);
    }
}
?>