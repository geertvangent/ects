<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

use common\libraries\ToolbarItem;
use common\libraries\Theme;
use application\discovery\SortableTable;
use common\libraries\Translation;

class Module extends \application\discovery\module\faculty\Module
{

    function get_faculties_table($year)
    {
        $faculties = $this->get_faculties_data($year);
        
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $faculty_info_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\faculty_info\implementation\bamaflex', 
                array('data_source' => $data_source));
        
        $photo_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\photo\implementation\bamaflex', 
                array('data_source' => $data_source));
        
        foreach ($faculties as $key => $faculty)
        {
            $row = array();
            
            if ($faculty_info_module_instance)
            {
                $parameters = new \application\discovery\module\faculty_info\implementation\bamaflex\Parameters(
                        $faculty->get_id(), $faculty->get_source());
                $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $faculty->get_name() . '</a>';
            }
            else
            {
                $row[] = $faculty->get_name();
            }
            
            $row[] = $faculty->get_deans_string();
            
            if ($photo_module_instance)
            {
                $parameters = new \application\discovery\module\photo\Parameters($faculty->get_id());
                $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                $row[] = Theme :: get_image('logo/16', 'png', 
                        Translation :: get('TypeName', null, 
                                'application\discovery\module\photo\implementation\bamaflex'), $url, 
                        ToolbarItem :: DISPLAY_ICON, false, 'application\discovery\module\photo\implementation\bamaflex');
            }
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Name'), false);
        $table->set_header(1, Translation :: get('Deans'), false);
        if ($photo_module_instance)
        {
            $table->set_header(2, '', false);
        }
        
        return $table;
    }
}
?>