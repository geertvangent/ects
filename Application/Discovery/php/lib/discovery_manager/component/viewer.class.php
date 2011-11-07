<?php
namespace application\discovery;

use common\libraries\Translation;

use common\libraries\ToolbarItem;

use common\libraries\BreadcrumbTrail;

use common\libraries\ObjectTableOrder;

use common\libraries\EqualityCondition;

use common\libraries\Session;

use common\libraries\Theme;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\Redirect;
use common\libraries\Request;

use application\discovery\module\profile\Profile;
use application\discovery\module\profile\implementation\bamaflex\SettingsConnector;

/**
 * @author Hans De Bisschop
 * @package application.discovery
 */
class DiscoveryManagerViewerComponent extends DiscoveryManager
{

    function run()
    {
        $module_id = Request :: get(DiscoveryManager :: PARAM_MODULE_ID);
        $module_content_type = Request :: get(DiscoveryManager :: PARAM_CONTENT_TYPE);
        
        $order_by = array(new ObjectTableOrder(ModuleInstance :: PROPERTY_DISPLAY_ORDER));
        
        if (! $module_id)
        {
            if (! $module_content_type)
            {
                $module_content_type = ModuleInstance :: TYPE_USER;
            }
            $condition = new EqualityCondition(ModuleInstance :: PROPERTY_CONTENT_TYPE, $module_content_type);
            $current_module_instance = DiscoveryDataManager :: get_instance()->retrieve_module_instance_by_condition($condition, $order_by);
            $module_id = $current_module_instance->get_id();
        }
        else
        {
            $current_module_instance = DiscoveryDataManager :: get_instance()->retrieve_module_instance($module_id);
            $module_content_type = $current_module_instance->get_content_type();
        }
        
        switch ($module_content_type)
        {
            case ModuleInstance :: TYPE_USER :
                $module_parameters = array();
                $module_parameters[DiscoveryManager :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_INFORMATION;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: get_instance()->add_extra(new ToolbarItem(Translation :: get('Information'), Theme :: get_image_path() . 'action_information.png', $link));
                break;
            case ModuleInstance :: TYPE_INFORMATION :
                $module_parameters = array();
                $module_parameters[DiscoveryManager :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_USER;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: get_instance()->add_extra(new ToolbarItem(Translation :: get('User'), Theme :: get_image_path() . 'action_user.png', $link));
                break;
            case ModuleInstance :: TYPE_DETAILS :
                $module_parameters = array();
                $module_parameters[DiscoveryManager :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_USER;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: get_instance()->add_extra(new ToolbarItem(Translation :: get('User'), Theme :: get_image_path() . 'action_user.png', $link));
                $module_parameters = array();
                $module_parameters[DiscoveryManager :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_INFORMATION;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: get_instance()->add_extra(new ToolbarItem(Translation :: get('Information'), Theme :: get_image_path() . 'action_information.png', $link));
                break;
        }
        
        $current_module = Module :: factory($this, $current_module_instance);
        
        $tabs = new DynamicVisualTabsRenderer('discovery', $current_module->render());
        
        if ($current_module_instance->get_content_type() != ModuleInstance :: TYPE_DETAILS)
        {
            $condition = new EqualityCondition(ModuleInstance :: PROPERTY_CONTENT_TYPE, $current_module_instance->get_content_type());
            $module_instances = DiscoveryDataManager :: get_instance()->retrieve_module_instances($condition, null, null, $order_by);
            
            while ($module_instance = $module_instances->next_result())
            {
                $module = $module_instance->get_type() . '\Module';
                $module_parameters = $module :: get_module_parameters()->get_parameters();
                $module_parameters[DiscoveryManager :: PARAM_MODULE_ID] = $module_instance->get_id();
                $selected = ($module_id == $module_instance->get_id() ? true : false);
                $link = $this->get_url($module_parameters);
                $tabs->add_tab(new DynamicVisualTab($module_instance->get_id(), $module_instance->get_title(), Theme :: get_image_path($module_instance->get_type()) . 'logo/22.png', $link, $selected));
            }
        }
        else
        {
            $module = $current_module_instance->get_type() . '\Module';
            $module_parameters = $module :: get_module_parameters()->get_parameters();
            $module_parameters[DiscoveryManager :: PARAM_MODULE_ID] = $current_module_instance->get_id();
            $link = $this->get_url($module_parameters);
            $tabs->add_tab(new DynamicVisualTab($current_module_instance->get_id(), $current_module_instance->get_title(), Theme :: get_image_path($current_module_instance->get_type()) . 'logo/22.png', $link, true));
        }
        $this->display_header();
        echo $tabs->render();
        
        echo '<div id="legend">';
        echo LegendTable :: get_instance()->as_html();
        echo '</div>';
        
        $this->display_footer();
    }
}
?>