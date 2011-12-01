<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

use common\libraries\ToolbarItem;

use common\libraries\Theme;

use application\discovery\SortableTable;

use common\libraries\Translation;

class Module extends \application\discovery\module\faculty\Module
{

    function get_faculties_table($year = 0)
    {
        $faculties = $this->get_faculties_data($year);
        
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $training_module_instance = \application\discovery\Module :: exists('application\discovery\module\training\implementation\bamaflex', array(
                'data_source' => $data_source));
        
        foreach ($faculties as $key => $faculty)
        {
            $row = array();
            if (! $year)
            {
                $row[] = $faculty->get_year();
            }
            
            $row[] = $faculty->get_name();
            $row[] = $faculty->get_deans_string();
            
            if ($training_module_instance)
            {
                $parameters = new \application\discovery\module\training\implementation\bamaflex\Parameters($faculty->get_id(), $faculty->get_source());
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