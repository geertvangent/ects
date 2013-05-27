<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use common\libraries\Translation;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;
use common\libraries\Request;
use application\discovery\module\photo\DataManager;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        $this->set_breadcrumbs();
        
        $parameters = $this->get_application()->get_parameters();
        $parameters = array_merge($parameters, $this->get_module_parameters()->get_parameters());
        $parameters[\application\discovery\Manager :: PARAM_MODULE_ID] = Request :: get(
            \application\discovery\Manager :: PARAM_MODULE_ID);
        
        \application\discovery\HtmlDefaultRendition :: add_export_action(
            $this, 
            \application\discovery\HtmlRendition :: VIEW_ZIP);
        
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
        return \application\discovery\Rendition :: FORMAT_HTML;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \application\discovery\Rendition :: VIEW_DEFAULT;
    }
}
