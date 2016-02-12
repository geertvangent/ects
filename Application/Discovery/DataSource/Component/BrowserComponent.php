<?php
namespace Ehb\Application\Discovery\DataSource\Component;

use Ehb\Application\Discovery\DataSource\Storage\DataClass\Instance;
use Ehb\Application\Discovery\DataSource\Manager;
use Ehb\Application\Discovery\DataSource\Table\Instance\InstanceTable;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Format\Structure\ActionBar\ActionBarSearchForm;

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
        
        $html = array();
        
        $html[] = $this->render_header();
        $html[] = $this->buttonToolbarRenderer->render();
        $html[] = $table->as_html();
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
                new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_NAME), 
                '*' . $query . '*');
            $conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_DESCRIPTION), 
                '*' . $query . '*');
            
            $condition = new AndCondition($conditions);
            return $condition;
        }
        else
        {
            return null;
        }
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
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }
}
