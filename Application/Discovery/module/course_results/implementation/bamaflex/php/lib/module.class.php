<?php
namespace application\discovery\module\course_results\implementation\bamaflex;

use common\libraries\Display;

use common\libraries\Breadcrumb;

use common\libraries\BreadcrumbTrail;

use common\libraries\PropertiesTable;

use user\UserDataManager;

use common\libraries\Request;

use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\course_results\DataManager;
use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;

use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicContentTab;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

class Module extends \application\discovery\module\course_results\Module
{
    const PARAM_SOURCE = 'source';

    /**
     *
     * @return multitype:multitype:string
     */
    function get_table_data()
    {
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $profile_module_instance = \application\discovery\Module :: exists('application\discovery\module\profile\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        foreach ($this->get_course_results() as $course_result)
        {
            $row = array();
            
            if ($profile_module_instance)
            {
                $user = UserDataManager :: get_instance()->retrieve_user_by_official_code($course_result->get_person_id());
                if ($user)
                {
                    $parameters = new \application\discovery\module\profile\Parameters($user->get_id());
                    $url = $this->get_instance_url($profile_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $course_result->get_person_last_name() . ' ' . $course_result->get_person_first_name() . '</a>';
                }
                else
                {
                    $row[] = $course_result->get_person_last_name() . ' ' . $course_result->get_person_first_name();
                }
            }
            else
            {
                $row[] = $course_result->get_person_last_name() . ' ' . $course_result->get_person_first_name();
            }
            
            $row[] = Translation :: get($course_result->get_trajectory_type_string(), null, 'application\discovery\module\enrollment\implementation\bamaflex');
            
            foreach ($this->get_mark_moments() as $mark_moment)
            {
                $mark = $course_result->get_mark_by_moment_id($mark_moment->get_id());
                
                if ($mark->get_result())
                {
                    $row[] = $mark->get_visual_result();
                }
                else
                {
                    $row[] = $mark->get_sub_status();
                }
                
                if ($mark->get_status())
                {
                    if ($mark->is_abandoned())
                    {
                        $mark_status_image = '<img src="' . Theme :: get_image_path('application\discovery\module\career\implementation\bamaflex') . 'status_type/' . $mark->get_status() . '_na.png" alt="' . Translation :: get($mark->get_status_string() . 'Abandoned', null, 'application\discovery\module\career\implementation\bamaflex') . '" title="' . Translation :: get($mark->get_status_string() . 'Abandoned', null, 'application\discovery\module\career\implementation\bamaflex') . '" />';
                        LegendTable :: get_instance()->add_symbol($mark_status_image, Translation :: get($mark->get_status_string() . 'Abandoned', null, 'application\discovery\module\career\implementation\bamaflex'), Translation :: get('MarkStatus', null, 'application\discovery\module\career\implementation\bamaflex'));
                    }
                    else
                    {
                        $mark_status_image = '<img src="' . Theme :: get_image_path('application\discovery\module\career\implementation\bamaflex') . 'status_type/' . $mark->get_status() . '.png" alt="' . Translation :: get($mark->get_status_string(), null, 'application\discovery\module\career\implementation\bamaflex') . '" title="' . Translation :: get($mark->get_status_string(), null, 'application\discovery\module\career\implementation\bamaflex') . '" />';
                        LegendTable :: get_instance()->add_symbol($mark_status_image, Translation :: get($mark->get_status_string(), null, 'application\discovery\module\career\implementation\bamaflex'), Translation :: get('MarkStatus', null, 'application\discovery\module\career\implementation\bamaflex'));
                    }
                    $row[] = $mark_status_image;
                }
                else
                {
                    $row[] = null;
                }
            }
            
            $data[] = $row;
        }
        
        return $data;
    }

    function get_course_results_parameters()
    {
        return self :: get_module_parameters();
    }

    static function get_module_parameters()
    {
        $programme = Request :: get(self :: PARAM_PROGRAMME_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        $parameter = new Parameters();
        
        if ($programme)
        {
            $parameter->set_programme_id($programme);
        }
        if ($source)
        {
            $parameter->set_source($source);
        }
        return $parameter;
    
    }

    /**
     *
     * @return multitype:string
     */
    function get_table_headers()
    {
        $headers = array();
        $headers[] = array(Translation :: get('PersonName'));
        $headers[] = array(Translation :: get('TrajectoryType'));
        
        foreach ($this->get_mark_moments() as $mark_moment)
        {
            $headers[] = array($mark_moment->get_name());
            $headers[] = array();
        }
        
        return $headers;
    }

    function get_course_results_table()
    {
        $html = array();
        
        $table_data = $this->get_table_data();
        if (count($table_data) > 0)
        {
            $table = new SortableTable($this->get_table_data());
            
            foreach ($this->get_table_headers() as $header_id => $header)
            {
                $table->set_header($header_id, $header[0], false);
                
                if ($header[1])
                {
                    $table->getHeader()->setColAttributes($header_id, $header[1]);
                }
            }
            
            $html[] = $table->toHTML();
        }
        
        return implode("\n", $html);
    }
    
    /*
     * (non-PHPdoc) @see
     * application\discovery\module\course_results.Module::render()
     */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        
        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, $this->get_module_instance()->get_id(), $this->get_course_results_parameters()))
        {
            Display :: not_allowed();
        }
        $html = array();
        $html[] = $this->get_course_properties_table() . '</br>';
        $html[] = $this->get_course_results_table();
        
        return implode("\n", $html);
    }

    static function get_course_parameters()
    {
        $programme_id = Request :: get(self :: PARAM_PROGRAMME_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        
        $parameter = new \application\discovery\module\course\implementation\bamaflex\Parameters();
        $parameter->set_programme_id($programme_id);
        
        if ($source)
        {
            $parameter->set_source($source);
        }
        
        return $parameter;
    }

    function get_course_properties_table()
    {
        $course = DataManager :: get_instance($this->get_module_instance())->retrieve_course($this->get_course_parameters());
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        
        $training_module_instance = \application\discovery\Module :: exists('application\discovery\module\training\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        $training_info_module_instance = \application\discovery\Module :: exists('application\discovery\module\training_info\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        $html = array();
        $properties = array();
        $properties[Translation :: get('Year')] = $course->get_year();
        
        $history = array();
        $courses = $course->get_all($this->get_module_instance());
        
        foreach ($courses as $course_history)
        {
            $parameters = new Parameters($course_history->get_id(), $course_history->get_source());
            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
            $history[] = '<a href="' . $link . '">' . $course_history->get_year() . '</a>';
        }
        $properties[Translation :: get('History')] = implode('  |  ', $history);
        
        if ($training_module_instance)
        {
            $parameters = new \application\discovery\module\training\implementation\bamaflex\Parameters($course->get_faculty_id(), $course->get_source());
            $url = $this->get_instance_url($training_module_instance->get_id(), $parameters);
            $properties[Translation :: get('Faculty')] = '<a href="' . $url . '">' . $course->get_faculty() . '</a>';
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb($url, $course->get_faculty()));
        }
        else
        {
            $properties[Translation :: get('Faculty')] = $course->get_faculty();
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $course->get_faculty()));
        }
        
        if ($training_info_module_instance)
        {
            $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters($course->get_training_id(), $course->get_source());
            $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
            $properties[Translation :: get('Training')] = '<a href="' . $url . '">' . $course->get_training() . '</a>';
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb($url, $course->get_training()));
        }
        else
        {
            $properties[Translation :: get('Training')] = $course->get_training();
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $course->get_training()));
        }
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $course->get_name()));
        
        $table = new PropertiesTable($properties);
        
        $html[] = $table->toHtml();
        return implode("\n", $html);
    }
}
?>