<?php
namespace Chamilo\Application\Discovery\Module\Training\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Application\Discovery\LegendTable;
use Chamilo\Application\Discovery\SortableTable;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        return \Chamilo\Application\Discovery\Module\Training\Rendition :: launch($this);
    }

    public function get_trainings_table($year)
    {
        $trainings = $this->get_trainings_data($year);
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $year));
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        
        $training_info_module_instance = \Chamilo\Application\Discovery\Module :: exists(
            'application\discovery\module\training_info\implementation\bamaflex', 
            array('data_source' => $data_source));
        $group_module_instance = \Chamilo\Application\Discovery\Module :: exists(
            'application\discovery\module\group\implementation\bamaflex', 
            array('data_source' => $data_source));
        $photo_module_instance = \Chamilo\Application\Discovery\Module :: exists(
            'application\discovery\module\photo\implementation\bamaflex', 
            array('data_source' => $data_source));
        $training_results_module_instance = \Chamilo\Application\Discovery\Module :: exists(
            'application\discovery\module\training_results\implementation\bamaflex', 
            array('data_source' => $data_source));
        
        foreach ($trainings as $key => $training)
        {
            $row = array();
            
            if ($training_info_module_instance)
            {
                $parameters = new \Chamilo\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters(
                    $training->get_id(), 
                    $training->get_source());
                
                $is_allowed = \Chamilo\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights :: is_allowed(
                    \Chamilo\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rights :: VIEW_RIGHT, 
                    $training_info_module_instance->get_id(), 
                    $parameters);
                
                if ($is_allowed)
                {
                    $url = $this->get_instance_url($training_info_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $training->get_name() . '</a>';
                }
                else
                {
                    $row[] = $training->get_name();
                }
            }
            else
            {
                $row[] = $training->get_name();
            }
            
            $row[] = $training->get_domain();
            $row[] = $training->get_credits();
            
            $bama_type_image = '<img src="' . Theme :: get_image_path() . 'bama_type/' . $training->get_bama_type() .
                 '.png" alt="' . Translation :: get($training->get_bama_type_string()) . '" title="' .
                 Translation :: get($training->get_bama_type_string()) . '" />';
            $row[] = $bama_type_image;
            LegendTable :: get_instance()->add_symbol(
                $bama_type_image, 
                Translation :: get($training->get_bama_type_string()), 
                Translation :: get('BamaType'));
            
            if ($group_module_instance || $photo_module_instance || $training_results_module_instance)
            {
                $buttons = array();
                
                if ($group_module_instance)
                {
                    $parameters = new \Chamilo\Application\Discovery\Module\Group\Implementation\Bamaflex\Parameters(
                        $training->get_id(), 
                        $training->get_source());
                    
                    $is_allowed = \Chamilo\Application\Discovery\Module\Group\Implementation\Bamaflex\Rights :: is_allowed(
                        \Chamilo\Application\Discovery\Module\Group\Implementation\Bamaflex\Rights :: VIEW_RIGHT, 
                        $group_module_instance->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($group_module_instance->get_id(), $parameters);
                        $toolbar_item = new ToolbarItem(
                            Translation :: get('Groups'), 
                            Theme :: get_image_path('application\discovery\module\group\implementation\bamaflex') .
                                 'logo/16.png', 
                                $url, 
                                ToolbarItem :: DISPLAY_ICON);
                    }
                    else
                    {
                        $toolbar_item = new ToolbarItem(
                            Translation :: get('GroupsNotAvailable'), 
                            Theme :: get_image_path('application\discovery\module\group\implementation\bamaflex') .
                                 'logo/16_na.png', 
                                null, 
                                ToolbarItem :: DISPLAY_ICON);
                    }
                    
                    $buttons[] = $toolbar_item->as_html();
                }
                
                if ($photo_module_instance)
                {
                    $parameters = new \Chamilo\Application\Discovery\Module\Photo\Parameters();
                    $parameters->set_training_id($training->get_id());
                    
                    $is_allowed = \Chamilo\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights :: is_allowed(
                        \Chamilo\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights :: VIEW_RIGHT, 
                        $photo_module_instance->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                        $buttons[] = Theme :: get_image(
                            'logo/16', 
                            'png', 
                            Translation :: get(
                                'TypeName', 
                                null, 
                                'application\discovery\module\photo\implementation\bamaflex'), 
                            $url, 
                            ToolbarItem :: DISPLAY_ICON, 
                            false, 
                            'application\discovery\module\photo\implementation\bamaflex');
                    }
                    else
                    {
                        $buttons[] = Theme :: get_image(
                            'logo/16_na', 
                            'png', 
                            Translation :: get(
                                'TypeName', 
                                null, 
                                'application\discovery\module\photo\implementation\bamaflex'), 
                            null, 
                            ToolbarItem :: DISPLAY_ICON, 
                            false, 
                            'application\discovery\module\photo\implementation\bamaflex');
                    }
                }
                
                if ($training_results_module_instance)
                {
                    $parameters = new \Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Parameters();
                    $parameters->set_training_id($training->get_id());
                    $parameters->set_source($training->get_source());
                    
                    $is_allowed = \Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rights :: is_allowed(
                        \Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rights :: VIEW_RIGHT, 
                        $training_results_module_instance->get_id(), 
                        $parameters);
                    
                    if ($is_allowed)
                    {
                        $url = $this->get_instance_url($training_results_module_instance->get_id(), $parameters);
                        $buttons[] = Theme :: get_image(
                            'logo/16', 
                            'png', 
                            Translation :: get(
                                'TypeName', 
                                null, 
                                'application\discovery\module\training_results\implementation\bamaflex'), 
                            $url, 
                            ToolbarItem :: DISPLAY_ICON, 
                            false, 
                            'application\discovery\module\training_results\implementation\bamaflex');
                    }
                    else
                    {
                        $buttons[] = Theme :: get_image(
                            'logo/16_na', 
                            'png', 
                            Translation :: get(
                                'TypeName', 
                                null, 
                                'application\discovery\module\training_results\implementation\bamaflex'), 
                            null, 
                            ToolbarItem :: DISPLAY_ICON, 
                            false, 
                            'application\discovery\module\training_results\implementation\bamaflex');
                    }
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
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Chamilo\Application\Discovery\Rendition :: FORMAT_HTML;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Chamilo\Application\Discovery\Rendition :: VIEW_DEFAULT;
    }
}
