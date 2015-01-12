<?php
namespace Chamilo\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Format\Breadcrumb;
use Chamilo\Libraries\Format\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Request;
use Chamilo\Libraries\Format\Display;
use Chamilo\Application\Discovery\Module\Photo\DataManager;
use Chamilo\Application\Discovery\AccessAllowedInterface;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        $this->set_breadcrumbs();
        
        $parameters = $this->get_application()->get_parameters();
        $parameters = array_merge($parameters, $this->get_module_parameters()->get_parameters());
        $parameters[\Chamilo\Application\Discovery\Manager :: PARAM_MODULE_ID] = Request :: get(
            \Chamilo\Application\Discovery\Manager :: PARAM_MODULE_ID);
        
        $application_is_allowed = $this->get_application() instanceof AccessAllowedInterface;
        
        if (! $application_is_allowed && ! Rights :: is_allowed(
            Rights :: VIEW_RIGHT, 
            $this->get_module_instance()->get_id(), 
            $this->get_module_parameters()))
        {
            Display :: not_allowed();
        }
        
        \Chamilo\Application\Discovery\HtmlDefaultRendition :: add_export_action(
            $this, 
            \Chamilo\Application\Discovery\HtmlRendition :: VIEW_ZIP);
        
        $table = new GalleryBrowserTable($this, $parameters, $this->get_module()->get_condition());
        return $table->as_html();
    }

    public function set_breadcrumbs()
    {
        $parameters = $this->get_module_parameters();
        $codes = array();
        
        if ($parameters->get_faculty_id())
        {
            $faculty = DataManager :: get_instance($this->get_module_instance())->retrieve_faculty(
                $parameters->get_faculty_id());
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $faculty->get_year()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $faculty->get_name()));
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case Module :: TYPE_TEACHER :
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Teachers')));
                        break;
                    case Module :: TYPE_STUDENT :
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Students')));
                        break;
                    case Module :: TYPE_EMPLOYEE :
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Employees')));
                        break;
                }
            }
        }
        elseif ($parameters->get_training_id())
        {
            $training = DataManager :: get_instance($this->get_module_instance())->retrieve_training(
                $parameters->get_training_id());
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_year()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_faculty()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_name()));
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case Module :: TYPE_TEACHER :
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Teachers')));
                        break;
                    case Module :: TYPE_STUDENT :
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Students')));
                        break;
                }
            }
        }
        elseif ($parameters->get_programme_id())
        {
            $programme = DataManager :: get_instance($this->get_module_instance())->retrieve_programme(
                $parameters->get_programme_id());
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $programme->get_year()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $programme->get_faculty()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $programme->get_training()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $programme->get_name()));
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case Module :: TYPE_TEACHER :
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Teachers')));
                        break;
                    case Module :: TYPE_STUDENT :
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Students')));
                        break;
                }
            }
        }
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
