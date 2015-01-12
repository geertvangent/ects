<?php
namespace Application\Discovery\module\person\implementation\chamilo\rendition\html;

use libraries\format\BreadcrumbTrail;
use libraries\format\Breadcrumb;
use libraries\format\TableSupport;
use libraries\format\Display;
use libraries\format\DynamicContentTab;
use libraries\format\ActionBarSearchForm;
use libraries\format\DynamicTabsRenderer;
use libraries\format\theme\Theme;
use libraries\utilities\Utilities;
use libraries\platform\translation\Translation;
use libraries\format\structure\ToolbarItem;
use libraries\format\ActionBarRenderer;
use libraries\storage\DataClassCountParameters;
use libraries\storage\EqualityCondition;
use application\discovery\RightsGroupEntityRight;
use libraries\platform\Session;
use libraries\storage\AndCondition;
use libraries\storage\InCondition;
use libraries\storage\OrCondition;
use libraries\storage\DataClassDistinctParameters;
use libraries\storage\DataClassRetrieveParameters;
use libraries\storage\PropertyConditionVariable;
use libraries\storage\StaticConditionVariable;

class HtmlDefaultRenditionImplementation extends RenditionImplementation implements TableSupport
{
    const TAB_SUBGROUPS = 0;
    const TAB_USERS = 1;
    const TAB_DETAILS = 2;

    private $action_bar;

    private $allowed_groups;

    private $current_group;

    public function render()
    {
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get(TypeName)));

        $this->action_bar = $this->get_action_bar();
        $html[] = $this->action_bar->as_html() . '<br />';
        $html[] = $this->get_menu_html();
        $html[] = $this->get_user_html();

        return implode("\n", $html);
    }

    public function get_action_bar()
    {
        $parameters = $this->get_application()->get_parameters(true);
        $parameters[\application\discovery\Manager :: PARAM_MODULE_ID] = $this->get_module_instance()->get_id();

        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        $action_bar->set_search_url($this->get_application()->get_url($parameters));

        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('Show', null, Utilities :: COMMON_LIBRARIES),
                Theme :: get_common_image_path() . 'action_browser.png',
                $this->get_application()->get_url(),
                ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        return $action_bar;
    }

    public function get_menu_html()
    {
        $url = $this->get_application()->get_url(
            array(
                \application\discovery\Manager :: PARAM_MODULE_ID => $this->get_module_instance()->get_id(),
                \core\group\Manager :: PARAM_GROUP_ID => '%s'));
        $group_menu = new GroupMenu($this, urldecode($url));

        $html = array();
        $html[] = '<div style="float: left; width: 25%; overflow: auto; height: 500px;">';
        $html[] = $group_menu->render_as_tree();
        $html[] = '</div>';

        return implode($html, "\n");
    }

    public function get_current_group()
    {
        if (! isset($this->current_group))
        {
            if ($this->get_group() == '0' || is_null($this->get_group()))
            {
                $condition = new EqualityCondition(
                    new PropertyConditionVariable(
                        \core\group\Group :: class_name(),
                        \core\group\Group :: PROPERTY_PARENT_ID),
                    new StaticConditionVariable(0));
                $group = \core\group\DataManager :: retrieve(
                    \core\group\Group :: class_name(),
                    new DataClassRetrieveParameters($condition));
                $this->current_group = $group;
            }
            else
            {
                $this->current_group = \core\group\DataManager :: retrieve_by_id(
                    \core\group\Group :: class_name(),
                    (int) $this->get_group());
            }
        }

        return $this->current_group;
    }

    public function get_user_html()
    {
        $current_group = $this->get_current_group();
        $show_users = false;
        $show_subgroups = false;
        if (! $this->get_context()->get_user()->is_platform_admin())
        {
            foreach ($this->get_allowed_groups() as $allowed_group_id)
            {
                if ($current_group->is_child_of($allowed_group_id) || $current_group->is_parent_of($allowed_group_id) ||
                     $allowed_group_id == $current_group->get_id())
                {
                    $show_subgroups = true;

                    if ($current_group->is_child_of($allowed_group_id) || $allowed_group_id == $current_group->get_id())
                    {
                        $show_users = true;
                        break;
                    }
                    else
                    {
                        $show_users = false;
                    }
                }
            }
        }
        else
        {
            $show_users = true;
            $show_subgroups = true;
        }

        $html = array();
        $html[] = '<div style="float: right; width: 73%;">';

        $renderer_name = Utilities :: get_classname_from_object($this, true);
        $tabs = new DynamicTabsRenderer($renderer_name);

        $parameters = $this->get_application()->get_parameters();
        $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->action_bar->get_query();
        $parameters[\core\group\Manager :: PARAM_GROUP_ID] = $this->get_group();
        $parameters[\application\discovery\Manager :: PARAM_MODULE_ID] = $this->get_module_instance()->get_id();

        $query = $this->action_bar->get_query();
        if (isset($query) && $query != '')
        {
            $count_users = \core\user\DataManager :: count(
                \core\user\User :: class_name(),
                new DataClassCountParameters($this->get_users_condition($this->action_bar->get_query())));
            $count_groups = \core\group\DataManager :: count(
                \core\group\Group :: class_name(),
                new DataClassCountParameters($this->get_subgroups_condition($this->action_bar->get_query())));

            if ($count_users > 0)
            {
                $table = new UserBrowserTable($this);
                $tabs->add_tab(
                    new DynamicContentTab(
                        self :: TAB_USERS,
                        Translation :: get('Users', null, 'user'),
                        Theme :: get_image_path(__NAMESPACE__) . 'tab/users.png',
                        $table->as_html()));
            }

            if ($count_groups > 0)
            {
                $table = new GroupBrowserTable(
                    $this,
                    $parameters,
                    $this->get_subgroups_condition($this->action_bar->get_query()));
                $tabs->add_tab(
                    new DynamicContentTab(
                        self :: TAB_SUBGROUPS,
                        Translation :: get('Subgroups'),
                        Theme :: get_image_path(__NAMESPACE__) . 'tab/groups.png',
                        $table->as_html()));
            }

            if ($count_users == 0 && $count_groups == 0)
            {
                $html[] = Display :: warning_message(
                    Translation :: get('NoSearchResults', null, Utilities :: COMMON_LIBRARIES),
                    true);
            }
        }
        else
        {
            if ($show_users)
            {
                $table = new GroupRelUserBrowserTable(
                    $this,
                    $parameters,
                    $this->get_users_condition($this->action_bar->get_query()));
                $tabs->add_tab(
                    new DynamicContentTab(
                        self :: TAB_USERS,
                        Translation :: get('Users', null, 'user'),
                        Theme :: get_image_path(__NAMESPACE__) . 'tab/users.png',
                        $table->as_html()));
            }

            if ($show_subgroups)
            {
                $count_groups = \core\group\DataManager :: count(
                    \core\group\Group :: class_name(),
                    new DataClassCountParameters($this->get_subgroups_condition($this->action_bar->get_query())));
                if ($count_groups > 0)
                {
                    $table = new GroupBrowserTable(
                        $this,
                        $parameters,
                        $this->get_subgroups_condition($this->action_bar->get_query()));
                    $tabs->add_tab(
                        new DynamicContentTab(
                            self :: TAB_SUBGROUPS,
                            Translation :: get('Subgroups'),
                            Theme :: get_image_path(__NAMESPACE__) . 'tab/groups.png',
                            $table->as_html()));
                }
            }

            $tabs->add_tab(
                new DynamicContentTab(
                    self :: TAB_DETAILS,
                    Translation :: get('Details'),
                    Theme :: get_image_path(__NAMESPACE__) . 'tab/details.png',
                    $this->get_group_info()));
        }

        $html[] = $tabs->render();

        $html[] = '</div>';
        $html[] = '<div class="clear"></div>';

        return implode($html, "\n");
    }

    public function get_group_info()
    {
        $group_id = $this->get_group();
        $group = \core\group\DataManager :: retrieve_by_id(\core\group\Group :: class_name(), (int) $group_id);

        $html = array();

        $html[] = '<b>' . Translation :: get('Code') . '</b>: ' . $group->get_code() . '<br />';

        $description = $group->get_description();
        if ($description)
        {
            $html[] = '<b>' . Translation :: get('Description', null, Utilities :: COMMON_LIBRARIES) . '</b>: ' .
                 $description . '<br />';
        }

        $html[] = '<br />';

        return implode("\n", $html);
    }

    public function get_group_viewing_url($group)
    {
        return $this->get_application()->get_url(
            array(
                \application\discovery\Manager :: PARAM_MODULE_ID => $this->get_module_instance()->get_id(),
                \core\group\Manager :: PARAM_GROUP_ID => $group->get_id()));
    }

    /*
     * (non-PHPdoc) @see \libraries\NewObjectTableSupport::get_object_table_condition()
     */
    public function get_object_table_condition($object_table_class_name)
    {
        return $this->get_users_condition($this->action_bar->get_query());
    }

    public function get_parameters()
    {
        $parameters = $this->get_application()->get_parameters();
        $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->action_bar->get_query();
        $parameters[\core\group\Manager :: PARAM_GROUP_ID] = $this->get_group();
        $parameters[\application\discovery\Manager :: PARAM_MODULE_ID] = $this->get_module_instance()->get_id();
        return $parameters;
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

    public function get_allowed_groups()
    {
        if (! isset($this->allowed_groups))
        {
            $current_user_group_ids = $this->get_context()->get_user()->get_groups(true);

            $conditions = array();
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_MODULE_ID),
                new StaticConditionVariable($this->get_module_instance()->get_id()));
            $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_RIGHT_ID, Rights :: VIEW_RIGHT);

            $entities_conditions = array();

            $user_entity_conditions = array();
            $user_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_ENTITY_ID),
                new StaticConditionVariable(Session :: get_user_id()));
            $user_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE),
                new StaticConditionVariable(\core\rights\UserEntity :: ENTITY_TYPE));
            $entities_conditions[] = new AndCondition($user_entity_conditions);

            $group_entity_conditions = array();
            $group_entity_conditions[] = new InCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_ENTITY_ID),
                $current_user_group_ids);
            $group_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE),
                new StaticConditionVariable(\core\rights\PlatformGroupEntity :: ENTITY_TYPE));
            $entities_conditions[] = new AndCondition($group_entity_conditions);

            $conditions[] = new OrCondition($entities_conditions);
            $condition = new AndCondition($conditions);

            $this->allowed_groups = \application\discovery\DataManager :: distinct(
                RightsGroupEntityRight :: class_name(),
                new DataClassDistinctParameters($condition, RightsGroupEntityRight :: PROPERTY_GROUP_ID));
        }

        return $this->allowed_groups;
    }
	/* (non-PHPdoc)
     * @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub

    }

}
