<?php
namespace application\discovery\module\training_results\implementation\bamaflex;

use common\libraries\Display;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;
use common\libraries\PropertiesTable;
use user\UserDataManager;
use common\libraries\Request;
use application\discovery\LegendTable;
use application\discovery\SortableTable;
use application\discovery\module\training_results\DataManager;
use application\discovery\module\enrollment\implementation\bamaflex\Enrollment;
use common\libraries\DynamicTabsRenderer;
use common\libraries\DynamicContentTab;
use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Utilities;
use common\libraries\DatetimeUtilities;
use common\libraries\Translation;

class Module extends \application\discovery\module\training_results\Module
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
        $profile_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\profile\implementation\bamaflex', 
                array('data_source' => $data_source));
        
        foreach ($this->get_training_results() as $enrollment)
        {
            $row = array();
            $user = UserDataManager :: get_instance()->retrieve_user_by_official_code($enrollment->get_person_id());
            
            if ($profile_module_instance)
            {
                if ($user)
                {
                    $parameters = new \application\discovery\module\profile\Parameters($user->get_id());
                    $url = $this->get_instance_url($profile_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $user->get_fullname() . '</a>';
                }
                else
                {
                    $row[] = $enrollment->get_optional_property('first_name') . ' ' . $enrollment->get_optional_property(
                            'last_name');
                }
            }
            else
            {
                if ($user)
                {
                    $row[] = $user->get_fullname();
                }
                else
                {
                    $row[] = $enrollment->get_optional_property('first_name') . ' ' . $enrollment->get_optional_property(
                            'last_name');
                }
            }
            
            $row[] = $enrollment->get_unified_option();
            $row[] = $enrollment->get_unified_trajectory();
            
            $row[] = Translation :: get($enrollment->get_contract_type_string(), null, 
                    'application\discovery\module\enrollment\implementation\bamaflex');
            if ($enrollment->is_special_result())
            {
                $image = '<img src="' . Theme :: get_image_path(
                        'application\discovery\module\enrollment\implementation\bamaflex') . 'result_type/' . $enrollment->get_result() . '.png" alt="' . Translation :: get(
                        $enrollment->get_result_string(), null, 
                        'application\discovery\module\enrollment\implementation\bamaflex') . '" title="' . Translation :: get(
                        $enrollment->get_result_string(), null, 
                        'application\discovery\module\enrollment\implementation\bamaflex') . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image, 
                        Translation :: get($enrollment->get_result_string(), null, 
                                'application\discovery\module\enrollment\implementation\bamaflex'), 
                        Translation :: get('ResultType', null, 
                                'application\discovery\module\enrollment\implementation\bamaflex'));
            }
            else
            {
                $row[] = ' ';
            }
            if ($enrollment->has_distinction())
            {
                $image = '<img src="' . Theme :: get_image_path(
                        'application\discovery\module\enrollment\implementation\bamaflex') . 'distinction_type/' . $enrollment->get_distinction() . '.png" alt="' . Translation :: get(
                        $enrollment->get_distinction_string(), null, 
                        'application\discovery\module\enrollment\implementation\bamaflex') . '" title="' . Translation :: get(
                        $enrollment->get_distinction_string(), null, 
                        'application\discovery\module\enrollment\implementation\bamaflex') . '" />';
                $row[] = $image;
                LegendTable :: get_instance()->add_symbol($image, 
                        Translation :: get($enrollment->get_distinction_string(), null, 
                                'application\discovery\module\enrollment\implementation\bamaflex'), 
                        Translation :: get('DistinctionType', null, 
                                'application\discovery\module\enrollment\implementation\bamaflex'));
            }
            else
            {
                $row[] = ' ';
            }
            
            $data[] = $row;
        }
        
        return $data;
    }

    function get_training_results_parameters()
    {
        return self :: get_module_parameters();
    }

    static function get_module_parameters()
    {
        $training_id = Request :: get(self :: PARAM_TRAINING_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        $parameter = new Parameters();
        
        if ($training_id)
        {
            $parameter->set_training_id($training_id);
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
        $headers[] = array(Translation :: get('Name'));
        $headers[] = array(Translation :: get('Option'));
        $headers[] = array(Translation :: get('Trajectory'));
        $headers[] = array(Translation :: get('Contract'));
        $headers[] = array('');
        $headers[] = array('');
        
        return $headers;
    }

    function get_training_results_table()
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
     * (non-PHPdoc) @see application\discovery\module\training_results.Module::render()
     */
    function render()
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        
        if (! Rights :: get_instance()->module_is_allowed(Rights :: VIEW_RIGHT, $entities, 
                $this->get_module_instance()->get_id(), $this->get_training_results_parameters()))
        {
            Display :: not_allowed();
        }
        $html = array();
        $html[] = $this->get_training_properties_table() . '</br>';
        $html[] = $this->get_training_results_table();
        
        return implode("\n", $html);
    }

    static function get_training_info_parameters()
    {
        $training_id = Request :: get(self :: PARAM_TRAINING_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        
        $parameter = new \application\discovery\module\training_info\implementation\bamaflex\Parameters();
        $parameter->set_training_id($training_id);
        
        if ($source)
        {
            $parameter->set_source($source);
        }
        
        return $parameter;
    }

    function get_training_properties_table()
    {
        $training = DataManager :: get_instance($this->get_module_instance())->retrieve_training(
                $this->get_training_info_parameters());
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        
        $faculty_info_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\faculty_info\implementation\bamaflex', 
                array('data_source' => $data_source));
        
        $html = array();
        $properties = array();
        $properties[Translation :: get('Year')] = $training->get_year();
        
        $history = array();
        $trainings = $training->get_all($this->get_module_instance());
        
        foreach ($trainings as $training_history)
        {
            $parameters = new Parameters($training_history->get_id(), $training_history->get_source());
            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
            $history[] = '<a href="' . $link . '">' . $training_history->get_year() . '</a>';
        }
        $properties[Translation :: get('History')] = implode('  |  ', $history);
        
        if ($faculty_info_module_instance)
        {
            $parameters = new \application\discovery\module\faculty_info\implementation\bamaflex\Parameters(
                    $training->get_faculty_id(), $training->get_source());
            $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
            $properties[Translation :: get('Faculty')] = '<a href="' . $url . '">' . $training->get_faculty() . '</a>';
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb($url, $training->get_faculty()));
        }
        else
        {
            $properties[Translation :: get('Faculty')] = $training->get_faculty();
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_faculty()));
        }
        
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_name()));
        
        $table = new PropertiesTable($properties);
        
        $html[] = $table->toHtml();
        return implode("\n", $html);
    }
}
?>