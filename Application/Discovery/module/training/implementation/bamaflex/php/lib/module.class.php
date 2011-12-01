<?php
namespace application\discovery\module\training\implementation\bamaflex;

use common\libraries\ToolbarItem;

use application\discovery\module\training\DataManager;

use common\libraries\PropertiesTable;

use common\libraries\Request;

use common\libraries\Theme;

use application\discovery\LegendTable;

use application\discovery\SortableTable;

use common\libraries\Translation;

class Module extends \application\discovery\module\training\Module
{
    const PARAM_FACULTY_ID = 'faculty_id';
    const PARAM_SOURCE = 'source';
    
    private $faculty;

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
        $this->faculty = DataManager :: get_instance($module_instance)->retrieve_faculty($this->get_faculty_parameters());
    }

    function get_training_parameters()
    {
        return self :: get_module_parameters();
    }

    function has_parameters()
    {
        if ($this->get_training_parameters()->get_source() && $this->get_training_parameters()->get_faculty_id())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    static function get_module_parameters()
    {
        $faculty = Request :: get(self :: PARAM_FACULTY_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        
        $parameter = new Parameters();
        if ($faculty)
        {
            $parameter->set_faculty_id($faculty);
        }
        if ($source)
        {
            $parameter->set_source($source);
        }
        return $parameter;
    
    }

    static function get_faculty_parameters()
    {
        $faculty = Request :: get(self :: PARAM_FACULTY_ID);
        $source = Request :: get(self :: PARAM_SOURCE);
        
        $parameter = new \application\discovery\module\faculty\implementation\bamaflex\Parameters();
        if ($faculty)
        {
            $parameter->set_faculty_id($faculty);
        }
        if ($source)
        {
            $parameter->set_source($source);
        }
        return $parameter;
    
    }

    function get_trainings_table($year = 0)
    {
        $trainings = $this->get_trainings_data($year);
        
        $data = array();
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $training_info_module_instance = \application\discovery\Module :: exists('application\discovery\module\training_info\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        foreach ($trainings as $key => $training)
        {
            $row = array();
            if (! $year && ! $this->has_parameters())
            {
                $row[] = $training->get_year();
            }
            
            if ($training_info_module_instance)
            {
                $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters($training->get_id(), $training->get_source());
                $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $training->get_name() . '</a>';
            }
            else
            {
                $row[] = $training->get_name();
            }
            
            $row[] = $training->get_domain();
            $row[] = $training->get_credits();
            
            $bama_type_image = '<img src="' . Theme :: get_image_path() . 'bama_type/' . $training->get_bama_type() . '.png" alt="' . Translation :: get($training->get_bama_type_string()) . '" title="' . Translation :: get($training->get_bama_type_string()) . '" />';
            $row[] = $bama_type_image;
            LegendTable :: get_instance()->add_symbol($bama_type_image, Translation :: get($training->get_bama_type_string()), Translation :: get('BamaType'));
            
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        if (! $year && ! $this->has_parameters())
        {
            $table->set_header(0, Translation :: get('Year'), false, 'class="code"');
            $table->set_header(1, Translation :: get('Name'), false);
            $table->set_header(2, Translation :: get('Domain'), false);
            $table->set_header(3, Translation :: get('Credits'), false);
            $table->set_header(4, Translation :: get(''), false);
        }
        else
        {
            $table->set_header(0, Translation :: get('Name'), false);
            $table->set_header(1, Translation :: get('Domain'), false);
            $table->set_header(2, Translation :: get('Credits'), false);
            $table->set_header(3, Translation :: get(''), false);
        }
        return $table;
    }

    function get_faculty_properties_table()
    {
        $properties = array();
        $properties[Translation :: get('Year')] = $this->faculty->get_year();
        $properties[Translation :: get('Deans')] = $this->faculty->get_deans_string();
        
        $history = array();
        $faculties = $this->faculty->get_all($this->get_module_instance());
        foreach ($faculties as $faculty)
        {
            $parameters = new Parameters($faculty->get_id(), $faculty->get_source());
            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
            $history[] = '<a href="' . $link . '">' . $faculty->get_year() . '</a>';
        }
        $properties[Translation :: get('History')] = implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $history);
        
        return new PropertiesTable($properties);
    }

    function get_context()
    {
        $html = array();
        
        $html[] = '<h3>';
        if ($this->faculty->get_previous_id())
        {
            $parameters = new Parameters($this->faculty->get_previous_id(), $this->faculty->get_source());
            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
            $html[] = Theme :: get_common_image('action_prev', 'png', Translation :: get('Previous'), $link, ToolbarItem :: DISPLAY_ICON);
        }
        else
        {
            $html[] = Theme :: get_common_image('action_prev_na', 'png', Translation :: get('PreviousNA'), null, ToolbarItem :: DISPLAY_ICON);
        
        }
        $html[] = $this->faculty->get_name();
        
        if ($this->faculty->get_next_id())
        {
            $parameters = new Parameters($this->faculty->get_next_id(), $this->faculty->get_source());
            $link = $this->get_instance_url($this->get_module_instance()->get_id(), $parameters);
            $html[] = Theme :: get_common_image('action_next', 'png', Translation :: get('Next'), $link, ToolbarItem :: DISPLAY_ICON);
        }
        else
        {
            $html[] = Theme :: get_common_image('action_next_na', 'png', Translation :: get('NextNA'), null, ToolbarItem :: DISPLAY_ICON);
        
        }
        $html[] = '</h3>';
        $html[] = $this->get_faculty_properties_table()->toHtml();
        $html[] = '<br/>';
        
        return implode("\n", $html);
    }
}
?>