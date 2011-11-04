<?php
namespace application\discovery\module\person\implementation\bamaflex;

use common\libraries\ToolbarItem;

use common\libraries\Theme;

use application\discovery\SortableTable;

use common\libraries\Translation;

class Module extends \application\discovery\module\person\Module
{

    function get_persons_table($year = 0)
    {
        $persons = $this->get_persons_data($year);
        
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $training_module_instance = \application\discovery\Module :: exists('application\discovery\module\training\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        foreach ($persons as $key => $person)
        {
            $row = array();
            if (! $year)
            {
                $row[] = $person->get_year();
            }
            
            $row[] = $person->get_name();
            $row[] = $person->get_deans_string();
            
            if ($training_module_instance)
            {
                $parameters = new \application\discovery\module\training\implementation\bamaflex\Parameters($person->get_id(), $person->get_source());
                $url = $this->get_instance_url($training_module_instance->get_id(), $parameters);
                $row[] = Theme :: get_common_image('action_details', 'png', Translation :: get('Trainings'), $url, ToolbarItem :: DISPLAY_ICON);
            }
            
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        if (! $year)
        {
            $table->set_header(0, Translation :: get('Year'), false, 'class="code"');
            $table->set_header(1, Translation :: get('Name'), false);
            $table->set_header(2, Translation :: get('Dean'), false);
            if ($training_module_instance)
            {
                $table->set_header(3, Translation :: get(''), false);
            }
        }
        else
        {
            $table->set_header(0, Translation :: get('Name'), false);
            $table->set_header(1, Translation :: get('Dean'), false);
            if ($training_module_instance)
            {
                $table->set_header(2, Translation :: get(''), false);
            }
        }
        return $table;
    }
}
?>