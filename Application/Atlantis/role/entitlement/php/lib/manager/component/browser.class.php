<?php
namespace application\atlantis\role\entitlement;

use common\libraries\Translation;
use application\atlantis\SessionBreadcrumbs;
use common\libraries\InCondition;
use common\libraries\AndCondition;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;
use common\libraries\DelegateComponent;
use common\libraries\Request;
use common\libraries\EqualityCondition;
use common\libraries\NewObjectTableSupport;

class BrowserComponent extends Manager implements NewObjectTableSupport, DelegateComponent
{

    private $right_id;

    private $role_id;

    private $application_id;

    public function get_object_table_condition($object_table_class_name)
    {
        if ($this->right_id && $this->application_id)
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(Entitlement :: PROPERTY_RIGHT_ID, $this->right_id);

            return new AndCondition($conditions);
        }
        if ($this->role_id)
        {
            return new EqualityCondition(Entitlement :: PROPERTY_ROLE_ID, $this->role_id);
        }
        if ($this->application_id)
        {
            $condition = new EqualityCondition(\application\atlantis\application\right\Right :: PROPERTY_APPLICATION_ID,
                    $this->application_id);
            $rights = \application\atlantis\application\right\DataManager :: retrieves(
                    \application\atlantis\application\right\Right :: class_name(), $condition);
            $right_ids = array();
            while ($right = $rights->next_result())
            {
                $right_ids[] = $right->get_id();
            }
            return new InCondition(Entitlement :: PROPERTY_RIGHT_ID, $right_ids);
        }
    }

    public function has_role_id()
    {
        return !empty($this->role_id);
    }

    public function has_right_id()
    {
        return !empty($this->right_id);
    }

    public function has_application_id()
    {
        return !empty($this->application_id);
    }

    public function run()
    {
        $this->right_id = Request :: get(\application\atlantis\application\right\Manager :: PARAM_RIGHT_ID);
        $this->role_id = Request :: get(\application\atlantis\role\Manager :: PARAM_ROLE_ID);
        $this->application_id = Request :: get(\application\atlantis\application\right\Manager :: PARAM_APPLICATION_ID);

        $this->add_breadcrumb();

        $this->display_header();
        $table = new EntitlementTable($this);
        echo ($table->as_html());
        $this->display_footer();
    }

    public function add_breadcrumb()
    {
        if ($this->has_application_id())
        {
            $application = \application\atlantis\application\DataManager :: retrieve(
                    \application\atlantis\application\Application :: class_name(), (int) $this->application_id);

            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $application->get_name()));
            if (! $this->has_right_id())
            {
                SessionBreadcrumbs :: add(
                        new Breadcrumb(
                                $this->get_url(
                                        array(
                                                \application\atlantis\application\Manager :: PARAM_APPLICATION_ID => $this->application_id)),
                                Translation :: get('GrantRights', array('TYPE' => $application->get_name()))));
            }
        }

        if ($this->has_right_id() && $this->has_application_id())
        {
            $right = \application\atlantis\application\right\DataManager :: retrieve(
                    \application\atlantis\application\right\Right :: class_name(), (int) $this->right_id);

            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $right->get_name()));
            SessionBreadcrumbs :: add(
                    new Breadcrumb(
                            $this->get_url(
                                    array(
                                            \application\atlantis\application\Manager :: PARAM_APPLICATION_ID => $this->application_id,
                                            \application\atlantis\application\right\Manager :: PARAM_RIGHT_ID => $this->right_id)),
                            Translation :: get('GrantRights', array('TYPE' => $right->get_name()))));
        }

        if ($this->has_role_id())
        {
            $role = \application\atlantis\role\DataManager :: retrieve(\application\atlantis\role\Role :: class_name(),
                    (int) $this->role_id);

            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $role->get_name()));
            SessionBreadcrumbs :: add(
                    new Breadcrumb(
                            $this->get_url(array(\application\atlantis\role\Manager :: PARAM_ROLE_ID => $this->role_id)),
                            Translation :: get('GrantRights', array('TYPE' => $role->get_name()))));
        }
    }
}
