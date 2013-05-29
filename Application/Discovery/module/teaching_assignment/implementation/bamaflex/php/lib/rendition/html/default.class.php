<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

use application\discovery\SortableTable;
use common\libraries\ToolbarItem;
use application\discovery\LegendTable;
use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\Display;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get(TypeName)));
        
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        
        if (! Rights :: get_instance()->module_is_allowed(
            Rights :: VIEW_RIGHT, 
            $entities, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            Display :: not_allowed();
        }
        
        if (is_null($this->get_module_parameters()->get_year()))
        {
            $years = $this->get_years();
            $current_year = $years[0];
        }
        else
        {
            $current_year = $this->get_module_parameters()->get_year();
        }
        $parameters = $this->get_module_parameters();
        $parameters->set_year($current_year);
        
        $html = array();
        
        if ($this->has_data())
        {
            $tabs = new DynamicVisualTabsRenderer(
                'teaching_assignment_list', 
                $this->get_teaching_assignments_table($parameters)->toHTML());
            
            foreach ($this->get_years() as $year)
            {
                $parameters = $this->get_module_parameters();
                $parameters->set_year($year);
                $tabs->add_tab(
                    new DynamicVisualTab(
                        $year, 
                        $year, 
                        null, 
                        $this->get_instance_url($this->get_module_instance()->get_id(), $parameters), 
                        $current_year == $year));
            }
            
            $html[] = $tabs->render();
            
            \application\discovery\HtmlDefaultRendition :: add_export_action($this);
        }
        else
        {
            $html[] = Display :: normal_message(Translation :: get('NoData'), true);
        }
        
        return implode("\n", $html);
    }

    public function get_teaching_assignments_table($parameters)
    {
        $teaching_assignments = $this->get_teaching_assignments_data($parameters);
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $course_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\course\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        $course_result_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\course_results\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        $faculty_info_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\faculty_info\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        $training_info_module_instance = \application\discovery\Module :: exists(
            'application\discovery\module\training_info\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        foreach ($teaching_assignments as $key => $teaching_assignment)
        {
            $row = array();
            
            if ($faculty_info_module_instance)
            {
                $parameters = new \application\discovery\module\faculty_info\implementation\bamaflex\Parameters(
                    $teaching_assignment->get_faculty_id(), 
                    $teaching_assignment->get_source());
                $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_faculty() . '</a>';
            }
            else
            {
                $row[] = $teaching_assignment->get_faculty();
            }
            
            if ($training_info_module_instance)
            {
                $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters(
                    $teaching_assignment->get_training_id(), 
                    $teaching_assignment->get_source());
                $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_training() . '</a>';
            }
            else
            {
                $row[] = $teaching_assignment->get_training();
            }
            
            $image = '<img src="' . Theme :: get_image_path() . 'type/' . $teaching_assignment->get_manager() .
                 '.png" alt="' . Translation :: get($teaching_assignment->get_manager_type()) . '" title="' .
                 Translation :: get($teaching_assignment->get_manager_type()) . '"/>';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol(
                $image, 
                Translation :: get($teaching_assignment->get_manager_type()), 
                Translation :: get('Manager'));
            
            $image = '<img src="' . Theme :: get_image_path() . 'type/' . $teaching_assignment->get_teacher() .
                 '.png" alt="' . Translation :: get($teaching_assignment->get_teacher_type()) . '" title="' .
                 Translation :: get($teaching_assignment->get_teacher_type()) . '"/>';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol(
                $image, 
                Translation :: get($teaching_assignment->get_teacher_type()), 
                Translation :: get('Teacher'));
            
            if ($course_module_instance)
            {
                $parameters = new \application\discovery\module\course\implementation\bamaflex\Parameters(
                    $teaching_assignment->get_programme_id(), 
                    $teaching_assignment->get_source());
                $url = $this->get_instance_url($course_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $teaching_assignment->get_name() . '</a>';
            }
            else
            {
                $row[] = $teaching_assignment->get_name();
            }
            $row[] = $teaching_assignment->get_credits();
            $image = '<img src="' . Theme :: get_image_path() . 'timeframe/' . $teaching_assignment->get_timeframe_id() .
                 '.png" alt="' . Translation :: get($teaching_assignment->get_timeframe()) . '" title="' .
                 Translation :: get($teaching_assignment->get_timeframe()) . '"/>';
            $row[] = $image;
            LegendTable :: get_instance()->add_symbol(
                $image, 
                Translation :: get($teaching_assignment->get_timeframe()), 
                Translation :: get('Timeframe'));
            
            if ($course_result_module_instance)
            {
                $parameters = new \application\discovery\module\course_results\implementation\bamaflex\Parameters(
                    $teaching_assignment->get_programme_id(), 
                    $teaching_assignment->get_source());
                $url = $this->get_instance_url($course_result_module_instance->get_id(), $parameters);
                $row[] = Theme :: get_common_image(
                    'action_details', 
                    'png', 
                    Translation :: get('CourseResults'), 
                    $url, 
                    ToolbarItem :: DISPLAY_ICON);
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
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \application\discovery\Rendition :: FORMAT_HTML;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \application\discovery\Rendition :: VIEW_DEFAULT;
    }
}
