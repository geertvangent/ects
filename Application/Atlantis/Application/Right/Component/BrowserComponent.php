<?php
namespace Ehb\Application\Atlantis\Application\Right\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
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
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Application\Right\Manager;
use Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right;
use Ehb\Application\Atlantis\Application\Right\Table\Right\RightTable;
use Ehb\Application\Atlantis\SessionBreadcrumbs;

class BrowserComponent extends Manager implements TableSupport, DelegateComponent
{

    /**
     *
     * @var ButtonToolBarRenderer
     */
    private $buttonToolbarRenderer;

    public function get_object_table_condition($object_table_class_name)
    {
        $query = $this->buttonToolbarRenderer->getSearchForm()->getQuery();
        ;
        
        $conditions = array();
        if (isset($query) && $query != '')
        {
            $search_conditions = array();
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Right::class_name(), Right::PROPERTY_NAME), 
                '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Right::class_name(), Right::PROPERTY_DESCRIPTION), 
                '*' . $query . '*');
            
            $conditions[] = new OrCondition($search_conditions);
        }
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Right::class_name(), Right::PROPERTY_APPLICATION_ID), 
            new StaticConditionVariable(
                $this->get_parameter(\Ehb\Application\Atlantis\Application\Manager::PARAM_APPLICATION_ID)));
        return new AndCondition($conditions);
    }

    public function run()
    {
        $this->add_breadcrumb();
        $this->buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        $table = new RightTable($this);
        
        $html = array();
        
        $html[] = $this->render_header();
        $html[] = $this->buttonToolbarRenderer->render();
        $html[] = $table->as_html();
        $html[] = $this->render_footer();
        
        return implode(PHP_EOL, $html);
    }

    public function getButtonToolbarRenderer()
    {
        if (! isset($this->buttonToolbarRenderer))
        {
            $buttonToolbar = new ButtonToolBar($this->get_url());
            $commonActions = new ButtonGroup();
            if ($this->get_user()->is_platform_admin())
            {
                $commonActions->addButton(
                    new Button(
                        Translation::get('Create', null, Utilities::COMMON_LIBRARIES), 
                        Theme::getInstance()->getCommonImagePath('Action/Create'), 
                        $this->get_url(array(self::PARAM_ACTION => self::ACTION_CREATE))));
            }
            
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }

    public function add_breadcrumb()
    {
        $application_id = Request::get(
            \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right::PROPERTY_APPLICATION_ID);
        $application = \Ehb\Application\Atlantis\Application\Storage\DataManager::retrieve_by_id(
            \Ehb\Application\Atlantis\Application\Storage\DataClass\Application::class_name(), 
            (int) $application_id);
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $application->get_name()));
        SessionBreadcrumbs::add(
            new Breadcrumb(
                $this->get_url(), 
                Translation::get('AvailableRights', array('TYPE' => $application->get_name()))));
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub
    }
}
