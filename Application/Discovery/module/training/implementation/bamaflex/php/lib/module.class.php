<?php
namespace application\discovery\module\training\implementation\bamaflex;

use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;
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

    function get_trainings_table($year)
    {
        $trainings = $this->get_trainings_data($year);
        
        $data = array();
        
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $training_info_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\training_info\implementation\bamaflex', 
                array('data_source' => $data_source));
        
        $group_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\group\implementation\bamaflex', 
                array('data_source' => $data_source));
        $photo_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\photo\implementation\bamaflex', 
                array('data_source' => $data_source));
        $training_results_module_instance = \application\discovery\Module :: exists(
                'application\discovery\module\training_results\implementation\bamaflex', 
                array('data_source' => $data_source));
        
        foreach ($trainings as $key => $training)
        {
            $row = array();
            
            if ($training_info_module_instance)
            {
                $parameters = new \application\discovery\module\training_info\implementation\bamaflex\Parameters(
                        $training->get_id(), $training->get_source());
                $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                $row[] = '<a href="' . $url . '">' . $training->get_name() . '</a>';
            }
            else
            {
                $row[] = $training->get_name();
            }
            
            $row[] = $training->get_domain();
            $row[] = $training->get_credits();
            
            $bama_type_image = '<img src="' . Theme :: get_image_path() . 'bama_type/' . $training->get_bama_type() . '.png" alt="' . Translation :: get(
                    $training->get_bama_type_string()) . '" title="' . Translation :: get(
                    $training->get_bama_type_string()) . '" />';
            $row[] = $bama_type_image;
            LegendTable :: get_instance()->add_symbol($bama_type_image, 
                    Translation :: get($training->get_bama_type_string()), Translation :: get('BamaType'));
            
            if ($group_module_instance || $photo_module_instance || $training_results_module_instance)
            {
                $buttons = array();
                
                if ($group_module_instance)
                {
                    $parameters = new \application\discovery\module\group\implementation\bamaflex\Parameters(
                            $training->get_id(), $training->get_source());
                    $url = $this->get_instance_url($group_module_instance->get_id(), $parameters);
                    $toolbar_item = new ToolbarItem(Translation :: get('Groups'), 
                            Theme :: get_image_path('application\discovery\module\group\implementation\bamaflex') . 'logo/16.png', 
                            $url, ToolbarItem :: DISPLAY_ICON);
                    
                    $buttons[] = $toolbar_item->as_html();
                }
                
                if ($photo_module_instance)
                {
                    $parameters = new \application\discovery\module\photo\Parameters();
                    $parameters->set_training_id($training->get_id());
                    
                    $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                    $buttons[] = Theme :: get_image('logo/16', 'png', 
                            Translation :: get('TypeName', null, 
                                    'application\discovery\module\photo\implementation\bamaflex'), $url, 
                            ToolbarItem :: DISPLAY_ICON, false, 
                            'application\discovery\module\photo\implementation\bamaflex');
                }
                
                if ($training_results_module_instance)
                {
                    $parameters = new \application\discovery\module\training_results\implementation\bamaflex\Parameters();
                    $parameters->set_training_id($training->get_id());
                    $parameters->set_source($training->get_source());
                    
                    $url = $this->get_instance_url($training_results_module_instance->get_id(), $parameters);
                    $buttons[] = Theme :: get_image('logo/16', 'png', 
                            Translation :: get('TypeName', null, 
                                    'application\discovery\module\training_results\implementation\bamaflex'), $url, 
                            ToolbarItem :: DISPLAY_ICON, false, 
                            'application\discovery\module\training_results\implementation\bamaflex');
                }
                
                $row[] = implode("\n", $buttons);
            }
            
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Name'), false);
        $table->set_header(1, Translation :: get('Domain'), false);
        $table->set_header(2, Translation :: get('Credits'), false);
        $table->set_header(3, '', false);
        $table->set_header(4, '', false);
        
        return $table;
    }
}
?>