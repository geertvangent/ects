<?php
namespace Ehb\Application\Atlantis\Role\Entitlement\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Atlantis\Role\Entitlement\Manager;
use Ehb\Application\Atlantis\Role\Entitlement\Storage\DataClass\Entitlement;
use Ehb\Application\Atlantis\Role\Entitlement\Table\Entitlement\EntitlementTable;
use Ehb\Application\Atlantis\SessionBreadcrumbs;

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
                    \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right :: class_name(),
                    \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right :: PROPERTY_APPLICATION_ID),
                new StaticConditionVariable($this->application_id));
            $rights = \Ehb\Application\Atlantis\Application\Right\Storage\DataManager :: retrieves(
                \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right :: class_name(),
                new DataClassRetrievesParameters($condition));
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
        $this->right_id = Request :: get(\Ehb\Application\Atlantis\Application\Right\Manager :: PARAM_RIGHT_ID);
        $this->role_id = Request :: get(\Ehb\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID);
        $this->application_id = Request :: get(
            \Ehb\Application\Atlantis\Application\Right\Manager :: PARAM_APPLICATION_ID);

        $this->add_breadcrumb();

        $table = new EntitlementTable($this);

        $html = array();

        $html[] = $this->render_header();
        $html[] = $table->as_html();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    public function add_breadcrumb()
    {
        if ($this->has_application_id())
        {
            $application = \Ehb\Application\Atlantis\Application\Storage\DataManager :: retrieve_by_id(
                \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(),
                (int) $this->application_id);

            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $application->get_name()));
            if (! $this->has_right_id())
            {
                SessionBreadcrumbs :: add(
                    new Breadcrumb(
                        $this->get_url(
                            array(
                                \Ehb\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID => $this->application_id)),
                        Translation :: get('GrantRights', array('TYPE' => $application->get_name()))));
            }
        }

        if ($this->has_right_id() && $this->has_application_id())
        {
            $right = \Ehb\Application\Atlantis\Application\Right\Storage\DataManager :: retrieve_by_id(
                \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right :: class_name(),
                (int) $this->right_id);

            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $right->get_name()));
            SessionBreadcrumbs :: add(
                new Breadcrumb(
                    $this->get_url(
                        array(
                            \Ehb\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID => $this->application_id,
                            \Ehb\Application\Atlantis\Application\Right\Manager :: PARAM_RIGHT_ID => $this->right_id)),
                    Translation :: get('GrantRights', array('TYPE' => $right->get_name()))));
        }

        if ($this->has_role_id())
        {
            $role = \Ehb\Application\Atlantis\Role\Storage\DataManager :: retrieve_by_id(
                \Ehb\Application\Atlantis\Role\Storage\DataClass\Role :: class_name(),
                (int) $this->role_id);

            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $role->get_name()));
            SessionBreadcrumbs :: add(
                new Breadcrumb(
                    $this->get_url(array(\Ehb\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID => $this->role_id)),
                    Translation :: get('GrantRights', array('TYPE' => $role->get_name()))));
        }
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub
    }
}
