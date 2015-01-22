<?php
namespace Ehb\Application\Atlantis\Application\Right\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
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
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Application\Right\Manager;
use Ehb\Application\Atlantis\Application\Right\Table\DataClass\Right;
use Ehb\Application\Atlantis\Application\Right\Table\Right\RightTable;
use Ehb\Application\Atlantis\SessionBreadcrumbs;

class BrowserComponent extends Manager implements TableSupport, DelegateComponent
{

    private $action_bar;

    public function get_object_table_condition($object_table_class_name)
    {
        $query = $this->action_bar->get_query();

        $conditions = array();
        if (isset($query) && $query != '')
        {
            $search_conditions = array();
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Right :: class_name(), Right :: PROPERTY_NAME),
                '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Right :: class_name(), Right :: PROPERTY_DESCRIPTION),
                '*' . $query . '*');

            $conditions[] = new OrCondition($search_conditions);
        }

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Right :: class_name(), Right :: PROPERTY_APPLICATION_ID),
            new StaticConditionVariable(
                $this->get_parameter(\Ehb\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID)));
        return new AndCondition($conditions);
    }

    public function run()
    {
        $this->add_breadcrumb();
        $this->display_header();
        $this->action_bar = $this->get_action_bar();
        echo ($this->action_bar->as_html());
        $table = new RightTable($this);
        echo ($table->as_html());
        $this->display_footer();
    }

    public function get_action_bar()
    {
        if (! isset($this->action_bar))
        {
            $this->action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);
            if ($this->get_user()->is_platform_admin())
            {
                $this->action_bar->add_common_action(
                    new ToolbarItem(
                        Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES),
                        Theme :: getInstance()->getCommonImagePath() . 'action_create.png',
                        $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE))));
            }
            $this->action_bar->set_search_url($this->get_url());
        }
        return $this->action_bar;
    }

    public function add_breadcrumb()
    {
        $application_id = Request :: get(
            \Ehb\Application\Atlantis\Application\Right\Table\DataClass\Right :: PROPERTY_APPLICATION_ID);
        $application = \Ehb\Application\Atlantis\Application\Storage\DataManager :: retrieve(
            \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(),
            (int) $application_id);
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $application->get_name()));
        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get('AvailableRights', array('TYPE' => $application->get_name()))));
    }
    /*
     * (non-PHPdoc) @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub
    }
}
