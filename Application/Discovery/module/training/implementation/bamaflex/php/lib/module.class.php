<?php
namespace application\discovery\module\training\implementation\bamaflex;

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
        $this->faculty = DataManager :: get_instance($module_instance)->retrieve_faculty($this->get_training_parameters());
    }

    function get_training_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_FACULTY_ID), Request :: get(self :: PARAM_SOURCE));
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
        return new PropertiesTable($properties);
    }

    function get_context()
    {
        $html = array();
        $html[] = '<h3>' . $this->faculty->get_name() . '</h3>';
        $html[] = $this->get_faculty_properties_table()->toHtml();
        $html[] = '<br/>';
        
        return implode("\n", $html);
    }
}
?>