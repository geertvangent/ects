<?php
use Chamilo\Libraries\Format\Structure\ActionBar\ActionBarSearchForm;
namespace Ehb\Application\Discovery\Instance\Component;

use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;
use Ehb\Application\Discovery\Instance\Manager;
use Ehb\Application\Discovery\Instance\Table\Instance\InstanceTable;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

class BrowserComponent extends Manager implements TableSupport
{

    /**
     *
     * @var ButtonToolBarRenderer
     */
    private $buttonToolbarRenderer;

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }
        
        $this->buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        $parameters = $this->get_parameters();
        $parameters[ActionBarSearchForm :: PARAM_SIMPLE_SEARCH_QUERY] = $this->buttonToolbarRenderer->getSearchForm()->getQuery();
        $table = new InstanceTable($this);
        
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
        
        $html = array();
        
        $html[] = $this->render_header();
        $html[] = $this->buttonToolbarRenderer->render();
        $html[] = $tabs->render();
        $html[] = $this->render_footer();
        
        return implode(PHP_EOL, $html);
    }

    public function get_table_condition($table_class_name)
    {
        $query = $this->buttonToolbarRenderer->getSearchForm()->getQuery();
        
        if (isset($query) && $query != '')
        {
            $conditions = array();
            $conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_TITLE), 
                '*' . $query . '*');
            $conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_DESCRIPTION), 
                '*' . $query . '*');
        }
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_CONTENT_TYPE), 
            new StaticConditionVariable($this->get_content_type()));
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

    public function getButtonToolbarRenderer()
    {
        if (! isset($this->buttonToolbarRenderer))
        {
            $buttonToolbar = new ButtonToolBar();
            $commonActions = new ButtonGroup();
            $commonActions->addButton(
                new Button(
                    Translation :: get('AddInstance'), 
                    Theme :: getInstance()->getCommonImagePath('Action/Create'), 
                    $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE_INSTANCE)), 
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            
            $commonActions->addButton(
                new Button(
                    Translation :: get('ManageDataSources'), 
                    Theme :: getInstance()->getCommonImagePath('Action/Config'), 
                    $this->get_url(
                        array(
                            \Ehb\Application\Discovery\Manager :: PARAM_ACTION => \Ehb\Application\Discovery\Manager :: ACTION_DATA_SOURCE, 
                            self :: PARAM_ACTION => null)), 
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }
}
