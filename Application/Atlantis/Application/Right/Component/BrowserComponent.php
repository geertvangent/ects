<?php
namespace Chamilo\Application\Atlantis\Application\Right\Component;

use Chamilo\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Application\Atlantis\Application\Right\Manager;
use Chamilo\Application\Atlantis\Application\Right\Table\DataClass\Right;
use Chamilo\Application\Atlantis\Application\Right\Table\Right\RightTable;

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
                $this->get_parameter(\Chamilo\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID)));
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
                        Theme :: get_common_image_path() . 'action_create.png',
                        $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE))));
            }
            $this->action_bar->set_search_url($this->get_url());
        }
        return $this->action_bar;
    }

    public function add_breadcrumb()
    {
        $application_id = Request :: get(
            \Chamilo\Application\Atlantis\Application\Right\Table\DataClass\Right :: PROPERTY_APPLICATION_ID);
        $application = \Chamilo\Application\Atlantis\Application\Storage\DataManager :: retrieve(
            \Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(),
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
