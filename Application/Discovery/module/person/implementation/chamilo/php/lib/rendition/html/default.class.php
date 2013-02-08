<?php
namespace application\discovery\module\person\implementation\chamilo;

use common\libraries\NewObjectTableSupport;
use common\libraries\Display;
use common\libraries\DynamicContentTab;
use common\libraries\ActionBarSearchForm;
use common\libraries\DynamicTabsRenderer;
use common\libraries\Theme;
use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\ToolbarItem;
use common\libraries\ActionBarRenderer;

class HtmlDefaultRenditionImplementation extends RenditionImplementation implements NewObjectTableSupport
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

    function get_action_bar()
    {
        $parameters = $this->get_application()->get_parameters(true);
        $parameters[\application\discovery\DiscoveryManager :: PARAM_MODULE_ID] = $this->get_module_instance()->get_id();

        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        $action_bar->set_search_url($this->get_application()->get_url($parameters));

        $action_bar->add_common_action(
                new ToolbarItem(Translation :: get('Show', null, Utilities :: COMMON_LIBRARIES),
                        Theme :: get_common_image_path() . 'action_browser.png', $this->get_application()->get_url(),
                        ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        return $action_bar;
    }

    function get_menu_html()
    {
        $url = $this->get_application()->get_url(
                array(
                        \application\discovery\DiscoveryManager :: PARAM_MODULE_ID => $this->get_module_instance()->get_id(),
                        \group\GroupManager :: PARAM_GROUP_ID => '%s'));
        $group_menu = new \group\GroupMenu($this->get_group(), urldecode($url));

        $html = array();
        $html[] = '<div style="float: left; width: 25%; overflow: auto; height: 500px;">';
        $html[] = $group_menu->render_as_tree();
        $html[] = '</div>';

        return implode($html, "\n");
    }

    function get_user_html()
    {
        $html = array();
        $html[] = '<div style="float: right; width: 73%;">';

        $renderer_name = Utilities :: get_classname_from_object($this, true);
        $tabs = new DynamicTabsRenderer($renderer_name);

        $parameters = $this->get_application()->get_parameters();
        $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->action_bar->get_query();
        $parameters[\group\GroupManager :: PARAM_GROUP_ID] = $this->get_group();
        $parameters[\application\discovery\DiscoveryManager :: PARAM_MODULE_ID] = $this->get_module_instance()->get_id();

        $query = $this->action_bar->get_query();
        if (isset($query) && $query != '')
        {
            $count_users = \user\DataManager :: count(\user\User :: class_name(),
                    $this->get_users_condition($this->action_bar->get_query()));
            $count_groups = \group\GroupDataManager :: get_instance()->count_groups(
                    $this->get_subgroups_condition($this->action_bar->get_query()));

            if ($count_users > 0)
            {
                $table = new UserBrowserTable($this);
                $tabs->add_tab(
                        new DynamicContentTab(self :: TAB_USERS, Translation :: get('Users', null, 'user'),
                                Theme :: get_image_path('user') . 'logo/' . Theme :: ICON_MINI . '.png',
                                $table->as_html()));
            }

            if ($count_groups > 0)
            {
                $table = new GroupBrowserTable($this, $parameters,
                        $this->get_subgroups_condition($this->action_bar->get_query()));
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
            $table = new GroupRelUserBrowserTable($this, $parameters,
                    $this->get_users_condition($this->action_bar->get_query()));
            $tabs->add_tab(
                    new DynamicContentTab(self :: TAB_USERS, Translation :: get('Users', null, 'user'),
                            Theme :: get_image_path('user') . 'logo/' . Theme :: ICON_MINI . '.png', $table->as_html()));

            $count_groups = \group\GroupDataManager :: get_instance()->count_groups(
                    $this->get_subgroups_condition($this->action_bar->get_query()));
            if ($count_groups > 0)
            {
                $table = new GroupBrowserTable($this, $parameters,
                        $this->get_subgroups_condition($this->action_bar->get_query()));
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

    function get_group_info()
    {
        $group_id = $this->get_group();
        $group = \group\GroupDataManager :: get_instance()->retrieve_group($group_id);

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

    function get_group_viewing_url($group)
    {
        return $this->get_application()->get_url(
                array(
                        \application\discovery\DiscoveryManager :: PARAM_MODULE_ID => $this->get_module_instance()->get_id(),
                        \group\GroupManager :: PARAM_GROUP_ID => $group->get_id()));
    }

    /*
     * (non-PHPdoc) @see \common\libraries\NewObjectTableSupport::get_object_table_condition()
     */
    public function get_object_table_condition($object_table_class_name)
    {
        return $this->get_users_condition($this->action_bar->get_query());
    }

    public function get_parameters()
    {
        $parameters = $this->get_application()->get_parameters();
        $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->action_bar->get_query();
        $parameters[\group\GroupManager :: PARAM_GROUP_ID] = $this->get_group();
        $parameters[\application\discovery\DiscoveryManager :: PARAM_MODULE_ID] = $this->get_module_instance()->get_id();
        return $parameters;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    function get_format()
    {
        return \application\discovery\Rendition :: FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    function get_view()
    {
        return \application\discovery\Rendition :: VIEW_DEFAULT;
    }
}
