<?php
namespace application\atlantis\application\right;

use application\atlantis\SessionBreadcrumbs;
use libraries\Breadcrumb;
use libraries\BreadcrumbTrail;
use libraries\Request;
use libraries\DelegateComponent;
use libraries\AndCondition;
use libraries\EqualityCondition;
use libraries\OrCondition;
use libraries\PatternMatchCondition;
use libraries\Theme;
use libraries\Utilities;
use libraries\Translation;
use libraries\ToolbarItem;
use libraries\ActionBarRenderer;
use libraries\NewObjectTableSupport;

class BrowserComponent extends Manager implements NewObjectTableSupport, DelegateComponent
{

    private $action_bar;

    public function get_object_table_condition($object_table_class_name)
    {
        $query = $this->action_bar->get_query();

        $conditions = array();
        if (isset($query) && $query != '')
        {
            $search_conditions = array();
            $search_conditions[] = new PatternMatchCondition(Right :: PROPERTY_NAME, '*' . $query . '*');
            $search_conditions[] = new PatternMatchCondition(Right :: PROPERTY_DESCRIPTION, '*' . $query . '*');
            $conditions[] = new OrCondition($search_conditions);
        }

        $conditions[] = new EqualityCondition(
            Right :: PROPERTY_APPLICATION_ID,
            $this->get_parameter(\application\atlantis\application\Manager :: PARAM_APPLICATION_ID));
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
        $application_id = Request :: get(\application\atlantis\application\right\Right :: PROPERTY_APPLICATION_ID);
        $application = \application\atlantis\application\DataManager :: retrieve(
            \application\atlantis\application\Application :: class_name(),
            (int) $application_id);
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $application->get_name()));
        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get('AvailableRights', array('TYPE' => $application->get_name()))));
    }
}
