<?php
namespace Chamilo\Application\Atlantis\Role\Entitlement\Component;

use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Storage\InCondition;
use Chamilo\Libraries\Storage\AndCondition;
use Chamilo\Libraries\Format\Breadcrumb;
use Chamilo\Libraries\Format\BreadcrumbTrail;
use Chamilo\Libraries\Architecture\DelegateComponent;
use Chamilo\Libraries\Platform\Request;
use Chamilo\Libraries\Storage\EqualityCondition;
use Chamilo\Libraries\Format\TableSupport;
use Chamilo\Libraries\Storage\StaticConditionVariable;
use Chamilo\Libraries\Storage\PropertyConditionVariable;

class BrowserComponent extends Manager implements TableSupport, DelegateComponent
{

    private $right_id;

    private $role_id;

    private $application_id;

    public function get_object_table_condition($object_table_class_name)
    {
        if ($this->right_id && $this->application_id)
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Entitlement :: class_name(), Entitlement :: PROPERTY_RIGHT_ID),
                new StaticConditionVariable($this->right_id));

            return new AndCondition($conditions);
        }
        if ($this->role_id)
        {
            return new EqualityCondition(
                new PropertyConditionVariable(Entitlement :: class_name(), Entitlement :: PROPERTY_ROLE_ID),
                new StaticConditionVariable($this->role_id));
        }
        if ($this->application_id)
        {
            $condition = new EqualityCondition(
                new PropertyConditionVariable(
                    \Chamilo\Application\Atlantis\Application\Right\Right :: class_name(),
                    \Chamilo\Application\Atlantis\Application\Right\Right :: PROPERTY_APPLICATION_ID),
                new StaticConditionVariable($this->application_id));
            $rights = \Chamilo\Application\Atlantis\Application\Right\DataManager :: retrieves(
                \Chamilo\Application\Atlantis\Application\Right\Right :: class_name(),
                $condition);
            $right_ids = array();
            while ($right = $rights->next_result())
            {
                $right_ids[] = $right->get_id();
            }
            return new InCondition(
                new PropertyConditionVariable(Entitlement :: class_name(), Entitlement :: PROPERTY_RIGHT_ID),
                $right_ids);
        }
    }

    public function has_role_id()
    {
        return ! empty($this->role_id);
    }

    public function has_right_id()
    {
        return ! empty($this->right_id);
    }

    public function has_application_id()
    {
        return ! empty($this->application_id);
    }

    public function run()
    {
        $this->right_id = Request :: get(\Chamilo\Application\Atlantis\Application\Right\Manager :: PARAM_RIGHT_ID);
        $this->role_id = Request :: get(\Chamilo\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID);
        $this->application_id = Request :: get(\Chamilo\Application\Atlantis\Application\Right\Manager :: PARAM_APPLICATION_ID);

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
            $application = \Chamilo\Application\Atlantis\Application\DataManager :: retrieve(
                \Chamilo\Application\Atlantis\Application\Application :: class_name(),
                (int) $this->application_id);

            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $application->get_name()));
            if (! $this->has_right_id())
            {
                SessionBreadcrumbs :: add(
                    new Breadcrumb(
                        $this->get_url(
                            array(
                                \Chamilo\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID => $this->application_id)),
                        Translation :: get('GrantRights', array('TYPE' => $application->get_name()))));
            }
        }

        if ($this->has_right_id() && $this->has_application_id())
        {
            $right = \Chamilo\Application\Atlantis\Application\Right\DataManager :: retrieve(
                \Chamilo\Application\Atlantis\Application\Right\Right :: class_name(),
                (int) $this->right_id);

            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $right->get_name()));
            SessionBreadcrumbs :: add(
                new Breadcrumb(
                    $this->get_url(
                        array(
                            \Chamilo\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID => $this->application_id,
                            \Chamilo\Application\Atlantis\Application\Right\Manager :: PARAM_RIGHT_ID => $this->right_id)),
                    Translation :: get('GrantRights', array('TYPE' => $right->get_name()))));
        }

        if ($this->has_role_id())
        {
            $role = \Chamilo\Application\Atlantis\Role\DataManager :: retrieve(
                \Chamilo\Application\Atlantis\Role\Role :: class_name(),
                (int) $this->role_id);

            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $role->get_name()));
            SessionBreadcrumbs :: add(
                new Breadcrumb(
                    $this->get_url(array(\Chamilo\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID => $this->role_id)),
                    Translation :: get('GrantRights', array('TYPE' => $role->get_name()))));
        }
    }
	/* (non-PHPdoc)
     * @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub

    }

}
