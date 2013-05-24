<?php
namespace application\discovery;

use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\Request;
use common\libraries\EqualityCondition;
use common\libraries\Translation;
use common\libraries\ActionBarRenderer;
use common\libraries\ActionBarSearchForm;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\AndCondition;
use common\libraries\PatternMatchCondition;

class ModuleInstanceManagerBrowserComponent extends ModuleInstanceManager
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
        $table = new ModuleInstanceBrowserTable($this, $parameters, $this->get_condition());
        
        $tabs = new DynamicVisualTabsRenderer('module', $table->as_html());
        $param = array();
        $param[self :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_INFORMATION;
        $selected = $this->get_content_type() == ModuleInstance :: TYPE_INFORMATION ? true : false;
        $tabs->add_tab(
            new DynamicVisualTab(
                ModuleInstance :: TYPE_INFORMATION, 
                Translation :: get('Information'), 
                null, 
                $this->get_url($param), 
                $selected));
        
        $param = array();
        $param[self :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_USER;
        $selected = $this->get_content_type() == ModuleInstance :: TYPE_USER ? true : false;
        $tabs->add_tab(
            new DynamicVisualTab(
                ModuleInstance :: TYPE_USER, 
                Translation :: get('User'), 
                null, 
                $this->get_url($param), 
                $selected));
        
        $param = array();
        $param[self :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_DETAILS;
        $selected = $this->get_content_type() == ModuleInstance :: TYPE_DETAILS ? true : false;
        $tabs->add_tab(
            new DynamicVisualTab(
                ModuleInstance :: TYPE_DETAILS, 
                Translation :: get('Details'), 
                null, 
                $this->get_url($param), 
                $selected));
        
        $param = array();
        $param[self :: PARAM_CONTENT_TYPE] = ModuleInstance :: TYPE_DISABLED;
        $selected = $this->get_content_type() == ModuleInstance :: TYPE_DISABLED ? true : false;
        $tabs->add_tab(
            new DynamicVisualTab(
                ModuleInstance :: TYPE_DISABLED, 
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
            $conditions[] = new PatternMatchCondition(ModuleInstance :: PROPERTY_TITLE, '*' . $query . '*');
            $conditions[] = new PatternMatchCondition(ModuleInstance :: PROPERTY_DESCRIPTION, '*' . $query . '*');
        }
        $conditions[] = new EqualityCondition(ModuleInstance :: PROPERTY_CONTENT_TYPE, $this->get_content_type());
        $condition = new AndCondition($conditions);
        return $condition;
    }

    public function get_content_type()
    {
        $content_type = Request :: get(self :: PARAM_CONTENT_TYPE);
        if (! isset($content_type))
        {
            $content_type = ModuleInstance :: TYPE_INFORMATION;
        }
        return $content_type;
    }

    public function get_action_bar()
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('AddModuleInstance'), 
                Theme :: get_common_image_path() . 'action_create.png', 
                $this->get_url(
                    array(
                        ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_CREATE_INSTANCE)), 
                ToolbarItem :: DISPLAY_ICON_AND_LABEL));
        return $action_bar;
    }
}
