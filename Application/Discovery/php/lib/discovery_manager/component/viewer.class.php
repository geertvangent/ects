<?php
namespace application\discovery;

use user\UserDataManager;
use common\libraries\DelegateComponent;
use common\libraries\Breadcrumb;
use common\libraries\Display;
use common\libraries\Translation;
use common\libraries\ToolbarItem;
use common\libraries\BreadcrumbTrail;
use common\libraries\ObjectTableOrder;
use common\libraries\EqualityCondition;
use common\libraries\Theme;
use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\Request;

/**
 *
 * @author Hans De Bisschop
 * @package application.discovery
 */
class DiscoveryManagerViewerComponent extends DiscoveryManager implements DelegateComponent
{

    function run()
    {
        $module_id = Request :: get(DiscoveryManager :: PARAM_MODULE_ID);
        $module_content_type = Request :: get(DiscoveryManager :: PARAM_CONTENT_TYPE);

        $order_by = array(new ObjectTableOrder(ModuleInstance :: PROPERTY_DISPLAY_ORDER));
        if ($this->get_user()->is_platform_admin())
        {
            $link = $this->get_url(
                    array(self :: PARAM_ACTION => self :: ACTION_MODULE, self :: PARAM_MODULE_ID => null));
            BreadcrumbTrail :: get_instance()->add_extra(
                    new ToolbarItem(Translation :: get('Modules'),
                            Theme :: get_common_image_path() . 'action_config.png', $link));
        }

        if (! $module_id)
        {
            if (! $module_content_type)
            {
                $module_content_type = ModuleInstance :: TYPE_USER;
            }
            $condition = new EqualityCondition(ModuleInstance :: PROPERTY_CONTENT_TYPE, $module_content_type);
            $current_module_instance = DataManager :: get_instance()->retrieve_module_instance_by_condition(
                    $condition, $order_by);
            if (! $current_module_instance)
            {
                $this->display_header();
                echo Display :: warning_message(Translation :: get('NoModuleInstance'), true);
                $this->display_footer();
            }
            $module_id = $current_module_instance->get_id();
        }
        else
        {
            $current_module_instance = DataManager :: get_instance()->retrieve_module_instance($module_id);
            $module_content_type = $current_module_instance->get_content_type();
        }

        $this->set_parameter(DiscoveryManager :: PARAM_MODULE_ID, $module_id);

        switch ($module_content_type)
        {
            case ModuleInstance :: TYPE_USER :
                $module_parameters = array();
                $module_parameters[self :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_INFORMATION;
                $module_parameters[self :: PARAM_MODULE_ID] = null;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: get_instance()->add_extra(
                        new ToolbarItem(Translation :: get('Information'),
                                Theme :: get_image_path() . 'action_information.png', $link));
                break;
            case ModuleInstance :: TYPE_INFORMATION :
                $module_parameters = array();
                $module_parameters[self :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_USER;
                $module_parameters[self :: PARAM_MODULE_ID] = null;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: get_instance()->add_extra(
                        new ToolbarItem(Translation :: get('User'), Theme :: get_image_path() . 'action_user.png', $link));
                break;
            case ModuleInstance :: TYPE_DETAILS :
                $module_parameters = array();
                $module_parameters[self :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_USER;
                $module_parameters[self :: PARAM_MODULE_ID] = null;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: get_instance()->add_extra(
                        new ToolbarItem(Translation :: get('User'), Theme :: get_image_path() . 'action_user.png', $link));
                $module_parameters = array();
                $module_parameters[self :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_INFORMATION;
                $module_parameters[self :: PARAM_MODULE_ID] = null;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: get_instance()->add_extra(
                        new ToolbarItem(Translation :: get('Information'),
                                Theme :: get_image_path() . 'action_information.png', $link));
                break;
        }

        $current_module = Module :: factory($this, $current_module_instance);
        $view = Request :: get(self :: PARAM_VIEW);

        if ($current_module_instance->get_content_type() != ModuleInstance :: TYPE_DETAILS)
        {
            $rendered_module = RenditionImplementation :: launch($current_module, Rendition :: FORMAT_HTML,
                    $view ? $view : Rendition :: VIEW_DEFAULT, $this);
            $tabs = new DynamicVisualTabsRenderer('discovery', $rendered_module);
            $condition = new EqualityCondition(ModuleInstance :: PROPERTY_CONTENT_TYPE,
                    $current_module_instance->get_content_type());
            $module_instances = DataManager :: get_instance()->retrieve_module_instances($condition, null,
                    null, $order_by);

            while ($module_instance = $module_instances->next_result())
            {

                $rights = $module_instance->get_type() . '\Rights';
                $module_class = $module_instance->get_type() . '\Module';

                $module_parameters = $module_class :: module_parameters();

                if ($module_content_type == ModuleInstance :: TYPE_USER)
                {
                    if (! $module_parameters->get_user_id())
                    {
                        $module_parameters->set_user_id($this->get_user_id());
                    }

                    $module = Module :: factory($this, $module_instance);

                    if ($module->has_data($module_parameters))
                    {
                        if ($rights :: get_instance()->is_visible($module_instance->get_id(), $module_parameters))
                        {
                            $module_parameters_array = $module_parameters->get_parameters();
                            $module_parameters_array[DiscoveryManager :: PARAM_MODULE_ID] = $module_instance->get_id();
                            $selected = ($module_id == $module_instance->get_id() ? true : false);
                            $link = $this->get_url($module_parameters_array);
                            $tabs->add_tab(
                                    new DynamicVisualTab($module_instance->get_id(),
                                            Translation :: get('TypeName', null, $module_instance->get_type()),
                                            Theme :: get_image_path($module_instance->get_type()) . 'logo/22.png', $link,
                                            $selected));
                        }
                    }
                }
                else
                {
                    $module_parameters_array = $module_parameters->get_parameters();
                    $module_parameters_array[DiscoveryManager :: PARAM_MODULE_ID] = $module_instance->get_id();
                    $selected = ($module_id == $module_instance->get_id() ? true : false);
                    $link = $this->get_url($module_parameters_array);
                    $tabs->add_tab(
                            new DynamicVisualTab($module_instance->get_id(),
                                    Translation :: get('TypeName', null, $module_instance->get_type()),
                                    Theme :: get_image_path($module_instance->get_type()) . 'logo/22.png', $link,
                                    $selected));
                }
            }
        }

        if ($current_module_instance->get_content_type() == ModuleInstance :: TYPE_USER)
        {
            $user_id = $module_parameters->get_user_id();
            $user = UserDataManager :: get_instance()->retrieve_user($user_id);
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $user->get_fullname()));
        }

        if ($current_module_instance->get_content_type() != ModuleInstance :: TYPE_DETAILS)
        {
            $content = $tabs->render();
        }
        else
        {
            BreadcrumbTrail :: get_instance()->add(
                    new Breadcrumb(null, Translation :: get('TypeName', null, $current_module_instance->get_type())));
            $content = RenditionImplementation :: launch($current_module, Rendition :: FORMAT_HTML,
                    $view ? $view : Rendition :: VIEW_DEFAULT, $this);
        }
        $this->display_header();
        echo $content;
        echo '<div id="legend">';
        echo LegendTable :: get_instance()->as_html();
        echo '</div>';

        $this->display_footer();
    }
}
