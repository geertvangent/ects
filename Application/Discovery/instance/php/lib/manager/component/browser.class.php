<?php
namespace application\discovery\instance;

use libraries\DynamicVisualTab;
use libraries\DynamicVisualTabsRenderer;
use libraries\Request;
use libraries\EqualityCondition;
use libraries\Translation;
use libraries\ActionBarRenderer;
use libraries\ActionBarSearchForm;
use libraries\ToolbarItem;
use libraries\Theme;
use libraries\AndCondition;
use libraries\PatternMatchCondition;

class BrowserComponent extends Manager
{

    private $action_bar;

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }
        
        $this->action_bar = $this->get_action_bar();
        $parameters = $this->get_parameters();
        $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->action_bar->get_query();
        $table = new InstanceBrowserTable($this, $parameters, $this->get_condition());
        
        $tabs = new DynamicVisualTabsRenderer('module', $table->as_html());
        $param = array();
        $param[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_INFORMATION;
        $selected = $this->get_content_type() == Instance :: TYPE_INFORMATION ? true : false;
        
        $tabs->add_tab(
            new DynamicVisualTab(
                Instance :: TYPE_INFORMATION, 
                Translation :: get('Information'), 
                null, 
                $this->get_url($param), 
                $selected));
        
        $param = array();
        $param[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_USER;
        $selected = $this->get_content_type() == Instance :: TYPE_USER ? true : false;
        
        $tabs->add_tab(
            new DynamicVisualTab(
                Instance :: TYPE_USER, 
                Translation :: get('User'), 
                null, 
                $this->get_url($param), 
                $selected));
        
        $param = array();
        $param[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_DETAILS;
        $selected = $this->get_content_type() == Instance :: TYPE_DETAILS ? true : false;
        
        $tabs->add_tab(
            new DynamicVisualTab(
                Instance :: TYPE_DETAILS, 
                Translation :: get('Details'), 
                null, 
                $this->get_url($param), 
                $selected));
        
        $param = array();
        $param[self :: PARAM_CONTENT_TYPE] = Instance :: TYPE_DISABLED;
        $selected = $this->get_content_type() == Instance :: TYPE_DISABLED ? true : false;
        
        $tabs->add_tab(
            new DynamicVisualTab(
                Instance :: TYPE_DISABLED, 
                Translation :: get('Disabled'), 
                null, 
                $this->get_url($param), 
                $selected));
        
        $this->display_header();
        echo $this->action_bar->as_html();
        echo $tabs->render();
        $this->display_footer();
    }

    public function get_condition()
    {
        $query = $this->action_bar->get_query();
        
        if (isset($query) && $query != '')
        {
            $conditions = array();
            $conditions[] = new PatternMatchCondition(Instance :: PROPERTY_TITLE, '*' . $query . '*');
            $conditions[] = new PatternMatchCondition(Instance :: PROPERTY_DESCRIPTION, '*' . $query . '*');
        }
        
        $conditions[] = new EqualityCondition(Instance :: PROPERTY_CONTENT_TYPE, $this->get_content_type());
        $condition = new AndCondition($conditions);
        return $condition;
    }

    public function get_content_type()
    {
        $content_type = Request :: get(self :: PARAM_CONTENT_TYPE);
        
        if (! isset($content_type))
        {
            $content_type = Instance :: TYPE_INFORMATION;
        }
        
        return $content_type;
    }

    public function get_action_bar()
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        
        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('AddInstance'), 
                Theme :: get_common_image_path() . 'action_create.png', 
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE_INSTANCE)), 
                ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        
        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('ManageDataSources'), 
                Theme :: get_common_image_path() . 'action_config.png', 
                $this->get_url(
                    array(
                        \application\discovery\Manager :: PARAM_ACTION => \application\discovery\Manager :: ACTION_DATA_SOURCE, 
                        self :: PARAM_ACTION => null)), 
                ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        
        return $action_bar;
    }
}
