<?php
namespace Ehb\Application\Discovery\Module\Faculty\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\Module\Faculty\Implementation\Bamaflex\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\SortableTable;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('TypeName')));
        return \Ehb\Application\Discovery\Module\Faculty\Rendition\Rendition :: launch($this);
    }

    public function get_faculties_table($year)
    {
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $year));
        $faculties = $this->get_faculties_data($year);
        
        $data = array();
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $faculty_info_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\faculty_info\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        $photo_module_instance = \Ehb\Application\Discovery\Module :: exists(
            'Ehb\Application\Discovery\Module\photo\Implementation\Bamaflex', 
            array('data_source' => $data_source));
        
        foreach ($faculties as $key => $faculty)
        {
            $row = array();
            
            if ($faculty_info_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Parameters(
                    $faculty->get_id(), 
                    $faculty->get_source());
                
                $is_allowed = \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights :: is_allowed(
                    \Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rights :: VIEW_RIGHT, 
                    $faculty_info_module_instance->get_id(), 
                    $parameters);
                
                if ($is_allowed)
                {
                    $url = $this->get_instance_url($faculty_info_module_instance->get_id(), $parameters);
                    $row[] = '<a href="' . $url . '">' . $faculty->get_name() . '</a>';
                }
                else
                {
                    $row[] = $faculty->get_name();
                }
            }
            else
            {
                $row[] = $faculty->get_name();
            }
            
            $row[] = $faculty->get_deans_string();
            
            if ($photo_module_instance)
            {
                $parameters = new \Ehb\Application\Discovery\Module\Photo\Parameters($faculty->get_id());
                
                $is_allowed = \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights :: is_allowed(
                    \Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rights :: VIEW_RIGHT, 
                    $photo_module_instance->get_id(), 
                    $parameters);
                
                if ($is_allowed)
                {
                    $url = $this->get_instance_url($photo_module_instance->get_id(), $parameters);
                    $row[] = Theme :: getInstance()->getImage(
                        'Logo/16', 
                        'png', 
                        Translation :: get(
                            'TypeName', 
                            null, 
                            'Ehb\Application\Discovery\Module\photo\Implementation\Bamaflex'), 
                        $url, 
                        ToolbarItem :: DISPLAY_ICON, 
                        false, 
                        'Ehb\Application\Discovery\Module\photo\Implementation\Bamaflex');
                }
                else
                {
                    $row[] = Theme :: getInstance()->getImage(
                        'Logo/16_na', 
                        'png', 
                        Translation :: get(
                            'TypeName', 
                            null, 
                            'Ehb\Application\Discovery\Module\photo\Implementation\Bamaflex'), 
                        null, 
                        ToolbarItem :: DISPLAY_ICON, 
                        false, 
                        'Ehb\Application\Discovery\Module\photo\Implementation\Bamaflex');
                }
            }
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        $table->setColumnHeader(0, Translation :: get('Name'), false);
        $table->setColumnHeader(1, Translation :: get('Deans'), false);
        if ($photo_module_instance)
        {
            $table->setColumnHeader(2, '', false);
        }
        
        return $table;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: FORMAT_HTML;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: VIEW_DEFAULT;
    }
}
