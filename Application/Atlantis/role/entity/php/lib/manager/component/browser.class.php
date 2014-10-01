<?php
namespace application\atlantis\role\entity;

use libraries\Translation;
use application\atlantis\SessionBreadcrumbs;
use libraries\DelegateComponent;
use libraries\Breadcrumb;
use libraries\BreadcrumbTrail;
use libraries\AndCondition;
use libraries\EqualityCondition;
use libraries\Request;
use libraries\TableSupport;
use core\group\Group;
use libraries\PropertyConditionVariable;
use libraries\StaticConditionVariable;

class BrowserComponent extends Manager implements TableSupport, DelegateComponent
{

    private $entity_id;

    private $entity_type;

    private $context_id;

    private $role_id;

    private $start_date;

    private $end_date;

    /*
     * (non-PHPdoc) @see common\libraries.NewObjectTableSupport::get_object_table_condition()
     */
    public function get_object_table_condition($object_table_class_name)
    {
        $conditions = array();

        if ($this->entity_type && $this->entity_id)
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(RoleEntity :: class_name(), RoleEntity :: PROPERTY_ENTITY_ID),
                new StaticConditionVariable($this->entity_id));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(RoleEntity :: class_name(), RoleEntity :: PROPERTY_ENTITY_TYPE),
                new StaticConditionVariable($this->entity_type));
        }

        if ($this->context_id)
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(RoleEntity :: class_name(), RoleEntity :: PROPERTY_CONTEXT_ID),
                new StaticConditionVariable($this->context_id));
        }

        if ($this->role_id)
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(RoleEntity :: class_name(), RoleEntity :: PROPERTY_ROLE_ID),
                new StaticConditionVariable($this->role_id));
        }

        if ($this->start_date)
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(RoleEntity :: class_name(), RoleEntity :: PROPERTY_START_DATE),
                new StaticConditionVariable($this->start_date));
        }

        if ($this->end_date)
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(RoleEntity :: class_name(), RoleEntity :: PROPERTY_END_DATE),
                new StaticConditionVariable($this->end_date));
        }

        if (count($conditions) > 0)
        {
            return new AndCondition($conditions);
        }
        else
        {
            return null;
        }
    }

    public function has_role_id()
    {
        return isset($this->role_id);
    }

    public function get_role_id()
    {
        return $this->role_id;
    }

    public function has_context_id()
    {
        return isset($this->context_id);
    }

    public function get_context_id()
    {
        return $this->context_id;
    }

    public function has_entity()
    {
        return isset($this->entity_id) && isset($this->entity_type);
    }

    public function has_start_date()
    {
        return isset($this->start_date);
    }

    public function has_end_date()
    {
        return isset($this->end_date);
    }

    public function add_breadcrumb()
    {
        if ($this->has_context_id() && ! $this->has_entity() && ! $this->has_role_id())
        {
            $context = \core\group\DataManager :: retrieve_by_id(
                \core\group\Group :: class_name(),
                (int) $this->context_id);
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $context->get_name()));
            SessionBreadcrumbs :: add(
                new Breadcrumb(
                    $this->get_url(),
                    Translation :: get('GrantedContexts', array('TYPE' => $context->get_name()))));
        }
        elseif (! $this->has_context_id() && $this->has_entity() && ! $this->has_role_id())
        {
            // BreadcrumbTrail :: get_instance()->add(
            // new Breadcrumb(null, RoleEntity :: entity_name($this->entity_type, $this->entity_id)));
        }
        elseif (! $this->has_context_id() && ! $this->has_entity() && $this->has_role_id())
        {
            $role = \application\atlantis\role\DataManager :: retrieve_by_id(
                \application\atlantis\role\Role :: class_name(),
                (int) $this->role_id);
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $role->get_name()));
            SessionBreadcrumbs :: add(
                new Breadcrumb($this->get_url(), Translation :: get('GrantedRoles', array('TYPE' => $role->get_name()))));
        }
        elseif ($this->has_context_id() && $this->has_entity() && ! $this->has_role_id())
        {
            // no entity
        }
        elseif ($this->has_context_id() && ! $this->has_entity() && $this->has_role_id())
        {
            $context = \core\group\DataManager :: retrieve_by_id(Group :: class_name(), (int) $this->context_id);

            $role = \application\atlantis\role\DataManager :: retrieve_by_id(
                \application\atlantis\role\Role :: class_name(),
                (int) $this->role_id);

            SessionBreadcrumbs :: add(
                new Breadcrumb(
                    $this->get_url(),
                    Translation :: get(
                        'GrantedContextsRoles',
                        array('CONTEXT' => $context->get_name(), 'ROLE' => $role->get_name()))));
        }
        elseif (! $this->has_context_id() && $this->has_entity() && $this->has_role_id())
        {
            // no entity
        }

        elseif ($this->has_context_id() && $this->has_entity() && $this->has_role_id())
        {
            // no entity
        }
        else
        {
            SessionBreadcrumbs :: add(new Breadcrumb($this->get_url(), Translation :: get('Granted')));
        }
    }

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $this->entity_type = Request :: get(self :: PARAM_ENTITY_TYPE);
        $this->entity_id = Request :: get(self :: PARAM_ENTITY_ID);
        $this->context_id = Request :: get(\application\atlantis\context\Manager :: PARAM_CONTEXT_ID);
        $this->role_id = Request :: get(\application\atlantis\role\Manager :: PARAM_ROLE_ID);
        $this->start_date = Request :: get(self :: PARAM_START_DATE);
        $this->end_date = Request :: get(self :: PARAM_END_DATE);

        $this->add_breadcrumb();

        $this->display_header();
        $table = new RoleEntityTable($this);
        echo ($table->as_html());

        $this->display_footer();
    }
    /*
     * (non-PHPdoc) @see \libraries\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub
    }
}
