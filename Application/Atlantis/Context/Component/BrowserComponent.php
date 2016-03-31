<?php
namespace Ehb\Application\Atlantis\Context\Component;

use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Atlantis\Context\Manager;
use Ehb\Application\Atlantis\Context\Menu;
use Ehb\Application\Atlantis\Context\Table\Context\ContextTable;
use Ehb\Application\Atlantis\SessionBreadcrumbs;

class BrowserComponent extends Manager implements TableSupport
{

    /**
     *
     * @var ButtonToolBarRenderer
     */
    private $buttonToolbarRenderer;

    public function get_object_table_condition($object_table_class_name)
    {
        $query = $this->buttonToolbarRenderer->getSearchForm()->getQuery();
        $conditions = array();
        
        if (isset($query) && $query != '')
        {
            $search_conditions = array();
            
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_NAME), 
                '*' . $query . '*');
            
            $conditions[] = new OrCondition($search_conditions);
        }
        if ($this->get_context() != 0)
        {
            $context = \Chamilo\Core\Group\Storage\DataManager :: retrieve_by_id(
                Group :: class_name(), 
                (int) $this->get_context());
        }
        else
        {
            $context = new Group();
            $context->set_id(0);
            $context->set_name(Translation :: get('Root'));
        }
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_PARENT_ID), 
            new StaticConditionVariable($context->get_id()));
        
        return new AndCondition($conditions);
    }

    function get_context()
    {
        if (! $this->context)
        {
            $this->context = Request :: get(self :: PARAM_CONTEXT_ID);
            
            if (! $this->context)
            {
                $this->context = 0;
            }
        }
        
        return $this->context;
    }

    public function run()
    {
        SessionBreadcrumbs :: add(new Breadcrumb($this->get_url(), Translation :: get('TypeName')));
        $this->buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        $this->set_parameter(Manager :: PARAM_CONTEXT_ID, $this->get_context());
        
        $table = new ContextTable($this);
        $menu = new Menu($this->get_context());
        
        $html = array();
        
        $html[] = $this->render_header();
        $html[] = '<div style="float: left; width: 30%; overflow:auto;">';
        $html[] = $menu->render_as_tree();
        $html[] = '</div>';
        $html[] = '<div style="float: right; width: 69%;">';
        $html[] = $this->buttonToolbarRenderer->render();
        $html[] = $table->as_html();
        $html[] = '</div>';
        $html[] = $this->render_footer();
        
        return implode(PHP_EOL, $html);
    }

    public function getButtonToolbarRenderer()
    {
        if (! isset($this->buttonToolbarRenderer))
        {
            $buttonToolbar = new ButtonToolBar();
            $commonActions = new ButtonGroup();
            
            $commonActions->addButton(
                new Button(
                    Translation :: get('TypeName', null, '\Ehb\Application\Atlantis\Role\Entity'), 
                    Theme :: getInstance()->getImagesPath('\Ehb\Application\Atlantis\Role\Entity') . 'Logo/16.png', 
                    $this->get_url(
                        array(
                            \Ehb\Application\Atlantis\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Manager :: ACTION_ROLE, 
                            \Ehb\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Manager :: ACTION_ENTITY, 
                            \Ehb\Application\Atlantis\Role\Entity\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Entity\Manager :: ACTION_BROWSE, 
                            Manager :: PARAM_CONTEXT_ID => $this->get_context())), 
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub
    }
}
