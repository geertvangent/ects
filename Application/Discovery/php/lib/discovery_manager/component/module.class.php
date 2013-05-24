<?php
namespace application\discovery;

use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\ToolbarItem;
use common\libraries\BreadcrumbTrail;

class DiscoveryManagerModuleComponent extends DiscoveryManager
{

    public function run()
    {
        $module_parameters = array();
        $module_parameters[self :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_USER;
        $module_parameters[self :: PARAM_MODULE_ID] = null;
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;
        $link = $this->get_url($module_parameters);
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(Translation :: get('User'), Theme :: get_image_path() . 'action_user.png', $link));
        $module_parameters = array();
        $module_parameters[self :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_INFORMATION;
        $module_parameters[self :: PARAM_MODULE_ID] = null;
        $module_parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;
        $link = $this->get_url($module_parameters);
        BreadcrumbTrail :: get_instance()->add_extra(
            new ToolbarItem(
                Translation :: get('Information'), 
                Theme :: get_image_path() . 'action_information.png', 
                $link));
        
        ModuleInstanceManager :: launch($this);
    }
}
