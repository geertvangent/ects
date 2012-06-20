<?php
namespace application\discovery\module\person\implementation\chamilo;

use common\libraries\NewObjectTableSupport;
use common\libraries\Display;
use user\UserDataManager;
use user\User;
use group\GroupRelUser;
use group\GroupManager;
use group\Group;
use group\GroupMenu;
use group\GroupDataManager;
use common\libraries\AndCondition;
use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
use common\libraries\Request;
use common\libraries\EqualityCondition;
use common\libraries\Theme;
use common\libraries\Utilities;
use common\libraries\ToolbarItem;
use common\libraries\ActionBarRenderer;
use common\libraries\OrCondition;
use common\libraries\PatternMatchCondition;
use common\libraries\ActionBarSearchForm;
use common\libraries\Translation;
use application\discovery\Parameters;
use application\discovery\DiscoveryManager;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\module\person\Module implements NewObjectTableSupport
{
    const TAB_SUBGROUPS = 0;
    const TAB_USERS = 1;
    const TAB_DETAILS = 2;

    private $action_bar;

    function render()
    {
        $this->action_bar = $this->get_action_bar();
        $html[] = $this->action_bar->as_html() . '<br />';
        $html[] = $this->get_menu_html();
        $html[] = $this->get_user_html();

        return implode("\n", $html);
    }

    function get_group_viewing_url($group)
    {
        return $this->get_application()->get_url(
                array(DiscoveryManager :: PARAM_MODULE_ID => $this->get_module_instance()->get_id(),
                        GroupManager :: PARAM_GROUP_ID => $group->get_id()));
    }

    function get_menu_html()
    {
        $url = $this->get_application()->get_url(
                array(DiscoveryManager :: PARAM_MODULE_ID => $this->get_module_instance()->get_id(),
                        GroupManager :: PARAM_GROUP_ID => '%s'));
        $group_menu = new GroupMenu($this->get_group(), urldecode($url));
        // $group_menu = new TreeMenu('GroupTreeMenu', new GroupTreeMenuDataProvider($this->get_url(),
        // $this->get_group()));
        $html = array();
        $html[] = '<div style="float: left; width: 25%; overflow: auto; height: 500px;">';
        $html[] = $group_menu->render_as_tree();
        $html[] = '</div>';

        return implode($html, "\n");
    }

    function get_group()
    {
        if (! $this->group)
        {
            $this->group = Request :: get(GroupManager :: PARAM_GROUP_ID);

            if (! $this->group)
            {
                $this->group = $this->get_root_group()->get_id();
            }
        }

        return $this->group;
    }

    function get_root_group()
    {
        if (! $this->root_group)
        {
            $group = GroupDataManager :: get_instance()->retrieve_groups(
                    new EqualityCondition(Group :: PROPERTY_PARENT, 0))->next_result();
            $this->root_group = $group;
        }

        return $this->root_group;
    }

    function get_user_html()
    {
        $html = array();
        $html[] = '<div style="float: right; width: 73%;">';

        $renderer_name = Utilities :: get_classname_from_object($this, true);
        $tabs = new DynamicTabsRenderer($renderer_name);

        $parameters = $this->get_application()->get_parameters();
        $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->action_bar->get_query();
        $parameters[GroupManager :: PARAM_GROUP_ID] = $this->get_group();
        $parameters[DiscoveryManager :: PARAM_MODULE_ID] = $this->get_module_instance()->get_id();

        $query = $this->action_bar->get_query();
        if (isset($query) && $query != '')
        {
            $count_users = UserDataManager :: get_instance()->count_users($this->get_users_condition());
            $count_groups = GroupDataManager :: get_instance()->count_groups($this->get_subgroups_condition());
            if ($count_users > 0)
            {
                $table = new UserBrowserTable($this, $parameters, $this->get_users_condition());
                $tabs->add_tab(
                        new DynamicContentTab(self :: TAB_USERS, Translation :: get('Users', null, 'user'),
                                Theme :: get_image_path('user') . 'logo/' . Theme :: ICON_MINI . '.png',
                                $table->as_html()));
            }
            if ($count_groups > 0)
            {
                $table = new GroupBrowserTable($this, $parameters, $this->get_subgroups_condition());
                $tabs->add_tab(
                        new DynamicContentTab(self :: TAB_SUBGROUPS, Translation :: get('Subgroups'),
                                Theme :: get_image_path('group') . 'logo/' . Theme :: ICON_MINI . '.png',
                                $table->as_html()));
            }
            if ($count_users == 0 && $count_groups == 0)
            {
                $html[] = Display :: warning_message(
                        Translation :: get('NoSearchResults', null, Utilities :: COMMON_LIBRARIES), true);
            }
        }
        else
        {
            $table = new GroupRelUserBrowserTable($this, $parameters, $this->get_users_condition());
            $tabs->add_tab(
                    new DynamicContentTab(self :: TAB_USERS, Translation :: get('Users', null, 'user'),
                            Theme :: get_image_path('user') . 'logo/' . Theme :: ICON_MINI . '.png', $table->as_html()));

            $count_groups = GroupDataManager :: get_instance()->count_groups($this->get_subgroups_condition());
            if ($count_groups > 0)
            {
                $table = new GroupBrowserTable($this, $parameters, $this->get_subgroups_condition());
                $tabs->add_tab(
                        new DynamicContentTab(self :: TAB_SUBGROUPS, Translation :: get('Subgroups'),
                                Theme :: get_image_path('group') . 'logo/' . Theme :: ICON_MINI . '.png',
                                $table->as_html()));
            }

            $tabs->add_tab(
                    new DynamicContentTab(self :: TAB_DETAILS, Translation :: get('Details'),
                            Theme :: get_image_path('help') . 'logo/' . Theme :: ICON_MINI . '.png',
                            $this->get_group_info()));
        }

        $html[] = $tabs->render();

        $html[] = '</div>';
        $html[] = '<div class="clear"></div>';

        return implode($html, "\n");
    }

    function get_subgroups_condition()
    {
        $query = $this->action_bar->get_query();
        if (isset($query) && $query != '')
        {
            $or_conditions = array();
            $or_conditions[] = new PatternMatchCondition(Group :: PROPERTY_NAME, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(Group :: PROPERTY_DESCRIPTION, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(Group :: PROPERTY_CODE, '*' . $query . '*');
            return new OrCondition($or_conditions);
        }
        else
        {
            $condition = new EqualityCondition(Group :: PROPERTY_PARENT, $this->get_group());
        }

        return $condition;
    }

    function get_users_condition()
    {
        $query = $this->action_bar->get_query();

        if (isset($query) && $query != '')
        {
            $conditions = array();
            $or_conditions[] = new PatternMatchCondition(User :: PROPERTY_FIRSTNAME, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(User :: PROPERTY_LASTNAME, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(User :: PROPERTY_USERNAME, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*' . $query . '*');
            return new OrCondition($or_conditions);
        }
        else
        {
            return new EqualityCondition(GroupRelUser :: PROPERTY_GROUP_ID, $this->get_group());
        }
    }

    function get_group_info()
    {
        $group_id = $this->get_group();
        $group = GroupDataManager :: get_instance()->retrieve_group($group_id);

        $html = array();

        $html[] = '<b>' . Translation :: get('Code') . '</b>: ' . $group->get_code() . '<br />';

        $description = $group->get_description();
        if ($description)
        {
            $html[] = '<b>' . Translation :: get('Description', null, Utilities :: COMMON_LIBRARIES) . '</b>: ' . $description . '<br />';
        }

        $html[] = '<br />';

        return implode("\n", $html);
    }

    function get_action_bar()
    {
        $parameters = $this->get_application()->get_parameters(true);
        $parameters[DiscoveryManager :: PARAM_MODULE_ID] = $this->get_module_instance()->get_id();

        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        $action_bar->set_search_url($this->get_application()->get_url($parameters));

        $action_bar->add_common_action(
                new ToolbarItem(Translation :: get('Show', null, Utilities :: COMMON_LIBRARIES),
                        Theme :: get_common_image_path() . 'action_browser.png', $this->get_application()->get_url(),
                        ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        return $action_bar;
    }

    static function get_module_parameters()
    {
        return new Parameters();
    }
    /*
     * (non-PHPdoc) @see \common\libraries\NewObjectTableSupport::get_object_table_condition()
     */
    public function get_object_table_condition($object_table_class_name)
    {
        return $this->get_users_condition();
    }

    public function get_parameters()
    {
        return $this->get_application()->get_parameters();
    }
}
?>