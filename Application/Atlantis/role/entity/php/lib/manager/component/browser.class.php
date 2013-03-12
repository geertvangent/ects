<?php
namespace application\atlantis\role\entity;

use common\libraries\DelegateComponent;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;
use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\Request;
use common\libraries\NewObjectTableSupport;

class BrowserComponent extends Manager implements NewObjectTableSupport, DelegateComponent
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
            $conditions[] = new EqualityCondition(RoleEntity :: PROPERTY_ENTITY_ID, $this->entity_id);
            $conditions[] = new EqualityCondition(RoleEntity :: PROPERTY_ENTITY_TYPE, $this->entity_type);
        }
        if ($this->context_id)
        {
            $conditions[] = new EqualityCondition(RoleEntity :: PROPERTY_CONTEXT_ID, $this->context_id);
        }
        if ($this->role_id)
        {
            $conditions[] = new EqualityCondition(RoleEntity :: PROPERTY_ROLE_ID, $this->role_id);
        }
        if ($this->start_date)
        {
            $conditions[] = new EqualityCondition(RoleEntity :: PROPERTY_START_DATE, $this->start_date);
        }
        if ($this->end_date)
        {
            $conditions[] = new EqualityCondition(RoleEntity :: PROPERTY_END_DATE, $this->end_date);
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

    public function has_context_id()
    {
        return isset($this->context_id);
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
        if ($this->has_role_id())
        {
            BreadcrumbTrail :: get_instance()->add(
                    new Breadcrumb(null,
                            \application\atlantis\role\DataManager :: retrieve(
                                    \application\atlantis\role\Role :: class_name(), (int) $this->role_id)->get_name()));
        }
        if ($this->has_context_id())
        {
            BreadcrumbTrail :: get_instance()->add(
                    new Breadcrumb(null,
                            \application\atlantis\context\DataManager :: retrieve(
                                    \application\atlantis\context\Context :: class_name(), (int) $this->context_id)->get_context_name()));
        }
        if ($this->has_entity())
        {
            BreadcrumbTrail :: get_instance()->add(
                    new Breadcrumb(null, RoleEntity :: entity_name($this->entity_type, $this->entity_id)));
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
}
