<?php
namespace Ehb\Application\Discovery\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;
use Ehb\Application\Discovery\LegendTable;
use Ehb\Application\Discovery\Manager;
use Ehb\Application\Discovery\Module;
use Ehb\Application\Discovery\Rendition\Rendition;
use Ehb\Application\Discovery\Rendition\RenditionImplementation;

/**
 *
 * @author Hans De Bisschop
 * @package application.discovery
 */
class ViewerComponent extends Manager implements DelegateComponent
{

    public function run()
    {
        $module_id = Request :: get(Manager :: PARAM_MODULE_ID);
        $module_content_type = Request :: get(Manager :: PARAM_CONTENT_TYPE);

        $order_by = array(
            new OrderBy(new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_DISPLAY_ORDER)));
        if ($this->get_user()->is_platform_admin())
        {
            $link = $this->get_url(
                array(self :: PARAM_ACTION => self :: ACTION_MODULE, self :: PARAM_MODULE_ID => null));
            BreadcrumbTrail :: getInstance()->add_extra(
                new ToolbarItem(
                    Translation :: get('Modules'),
                    Theme :: getInstance()->getCommonImagePath('Action/Config'),
                    $link));
        }

        if (! $module_id)
        {
            if (! $module_content_type)
            {
                $module_content_type = Instance :: TYPE_USER;
            }

            $condition = new EqualityCondition(
                new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_CONTENT_TYPE),
                new StaticConditionVariable($module_content_type));
            $current_module_instance = \Ehb\Application\Discovery\Instance\Storage\DataManager :: retrieve(
                Instance :: class_name(),
                new DataClassRetrieveParameters($condition, $order_by));

            if (! $current_module_instance)
            {
                throw new \Exception(Translation :: get('NoInstance'));
            }

            $module_id = $current_module_instance->get_id();
        }
        else
        {
            $current_module_instance = \Ehb\Application\Discovery\Instance\Storage\DataManager :: retrieve_by_id(
                Instance :: class_name(),
                (int) $module_id);
            $module_content_type = $current_module_instance->get_content_type();
        }

        $this->set_parameter(Manager :: PARAM_MODULE_ID, $module_id);

        switch ($module_content_type)
        {
            case Instance :: TYPE_USER :
                $module_parameters = array();
                $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_INFORMATION;
                $module_parameters[self :: PARAM_MODULE_ID] = null;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: getInstance()->add_extra(
                    new ToolbarItem(
                        Translation :: get('Information'),
                        Theme :: getInstance()->getImagesPath('Ehb\Application\Discovery') . 'Action/Information.png',
                        $link));
                break;
            case Instance :: TYPE_INFORMATION :
                $module_parameters = array();
                $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_USER;
                $module_parameters[self :: PARAM_MODULE_ID] = null;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: getInstance()->add_extra(
                    new ToolbarItem(
                        Translation :: get('User'),
                        Theme :: getInstance()->getImagesPath('Ehb\Application\Discovery') . 'Action/User.png',
                        $link));
                break;
            case Instance :: TYPE_DETAILS :
                $module_parameters = array();
                $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_USER;
                $module_parameters[self :: PARAM_MODULE_ID] = null;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: getInstance()->add_extra(
                    new ToolbarItem(
                        Translation :: get('User'),
                        Theme :: getInstance()->getImagesPath('Ehb\Application\Discovery') . 'Action/User.png',
                        $link));
                $module_parameters = array();
                $module_parameters[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_INFORMATION;
                $module_parameters[self :: PARAM_MODULE_ID] = null;
                $link = $this->get_url($module_parameters);
                BreadcrumbTrail :: getInstance()->add_extra(
                    new ToolbarItem(
                        Translation :: get('Information'),
                        Theme :: getInstance()->getImagesPath('Ehb\Application\Discovery') . 'Action/Information.png',
                        $link));
                break;
        }

        $current_module = Module :: factory($this, $current_module_instance);
        $view = Request :: get(self :: PARAM_VIEW);

        if ($current_module_instance->get_content_type() != Instance :: TYPE_DETAILS)
        {
            $rendered_module = RenditionImplementation :: launch(
                $current_module,
                Rendition :: FORMAT_HTML,
                $view ? $view : Rendition :: VIEW_DEFAULT,
                $this);

            $tabs = new DynamicVisualTabsRenderer('discovery', $rendered_module);
            $condition = new EqualityCondition(
                new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_CONTENT_TYPE),
                new StaticConditionVariable($current_module_instance->get_content_type()));
            $module_instances = \Ehb\Application\Discovery\Instance\Storage\DataManager :: retrieves(
                Instance :: class_name(),
                new DataClassRetrievesParameters($condition, null, null, $order_by));

            while ($module_instance = $module_instances->next_result())
            {

                $rights = $module_instance->get_type() . '\Rights';
                $module_class = $module_instance->get_type() . '\Module';

                $module_parameters = $module_class :: module_parameters();

                if ($module_content_type == Instance :: TYPE_USER)
                {
                    if (! $module_parameters->get_user_id())
                    {
                        $module_parameters->set_user_id($this->get_user_id());
                    }

                    $module = Module :: factory($this, $module_instance);

                    if ($module->has_data($module_parameters))
                    {
                        if ($rights :: is_visible($module_instance->get_id(), $module_parameters))
                        {
                            $module_parameters_array = $module_parameters->get_parameters();
                            $module_parameters_array[Manager :: PARAM_MODULE_ID] = $module_instance->get_id();
                            $selected = ($module_id == $module_instance->get_id() ? true : false);
                            $link = $this->get_url($module_parameters_array);
                            $tabs->add_tab(
                                new DynamicVisualTab(
                                    $module_instance->get_id(),
                                    Translation :: get('TypeName', null, $module_instance->get_type()),
                                    Theme :: getInstance()->getImagesPath($module_instance->get_type()) . 'Logo/22.png',
                                    $link,
                                    $selected));
                        }
                    }
                }
                else
                {
                    $module_parameters_array = $module_parameters->get_parameters();
                    $module_parameters_array[Manager :: PARAM_MODULE_ID] = $module_instance->get_id();
                    $selected = ($module_id == $module_instance->get_id() ? true : false);
                    $link = $this->get_url($module_parameters_array);
                    $tabs->add_tab(
                        new DynamicVisualTab(
                            $module_instance->get_id(),
                            Translation :: get('TypeName', null, $module_instance->get_type()),
                            Theme :: getInstance()->getImagesPath($module_instance->get_type()) . 'Logo/22.png',
                            $link,
                            $selected));
                }
            }
        }

        if ($current_module_instance->get_content_type() == Instance :: TYPE_USER)
        {
            $user_id = $module_parameters->get_user_id();
            $user = \Chamilo\Core\User\Storage\DataManager :: retrieve_by_id(
                \Chamilo\Core\User\Storage\DataClass\User :: class_name(),
                (int) $user_id);
            BreadcrumbTrail :: getInstance()->add(new Breadcrumb(null, $user->get_fullname()));
        }

        if ($current_module_instance->get_content_type() != Instance :: TYPE_DETAILS)
        {
            $content = $tabs->render();
        }
        else
        {
            BreadcrumbTrail :: getInstance()->add(
                new Breadcrumb(null, Translation :: get('TypeName', null, $current_module_instance->get_type())));
            $content = RenditionImplementation :: launch(
                $current_module,
                Rendition :: FORMAT_HTML,
                $view ? $view : Rendition :: VIEW_DEFAULT,
                $this);
        }

        $html = array();

        $html[] = $this->render_header();
        $html[] = $content;
        $html[] = '<div id="legend">';
        $html[] = LegendTable :: getInstance()->toHtml();
        $html[] = '</div>';
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }
}
