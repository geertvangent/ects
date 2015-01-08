<?php
namespace application\discovery;

use libraries\format\theme\Theme;
use libraries\platform\translation\Translation;
use libraries\format\structure\ToolbarItem;
use libraries\format\BreadcrumbTrail;
use application\discovery\instance\Instance;

class DataSourceComponent extends Manager
{

    public function run()
    {
        $module_parameters = array();
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_MODULE;
        
        $link = $this->get_url($module_parameters);
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(Translation :: get('Modules'), Theme :: get_common_image_path() . 'action_config.png', $link));
        
        $module_parameters = array();
        $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_USER;
        $module_parameters[self :: PARAM_MODULE_ID] = null;
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;
        
        $link = $this->get_url($module_parameters);
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(Translation :: get('User'), Theme :: get_image_path() . 'action_user.png', $link));
        
        $module_parameters = array();
        $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_INFORMATION;
        $module_parameters[self :: PARAM_MODULE_ID] = null;
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;
        
        $link = $this->get_url($module_parameters);
        
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(
                Translation :: get('Information'), 
                Theme :: get_image_path() . 'action_information.png', 
                $link));
        
        \libraries\architecture\Application :: launch(
            \application\discovery\data_source\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
