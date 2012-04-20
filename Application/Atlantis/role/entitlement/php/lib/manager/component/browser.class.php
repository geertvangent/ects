<?php
namespace application\atlantis\role\entitlement;

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
            // $conditions[] = new
            // EqualityCondition(\application\atlantis\application\right\Right
            // :: PROPERTY_APPLICATION_ID, $this->application_id);
            return new AndCondition($conditions);
        }
        if ($this->role_id)
        {
            return new EqualityCondition(Entitlement :: PROPERTY_ROLE_ID, $this->role_id);
        }
        if ($this->application_id)
        {
            $right = \application\atlantis\application\right\DataManager :: retrieve(\application\atlantis\application\right\Right :: class_name(), (int) $this->application_id);
            return new EqualityCondition(Entitlement :: PROPERTY_ROLE_ID, $right->get_id());
        }
    }

    function has_role_id()
    {
        return isset($this->role_id);
    }

    function has_right_id()
    {
        return isset($this->right_id);
    }

    function has_application_id()
    {
        return isset($this->application_id);
    }

    function run()
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

    function add_breadcrumb()
    {
        if ($this->has_application_id())
        {
            $application = \application\atlantis\application\DataManager :: retrieve(\application\atlantis\application\Application :: class_name(), (int) $this->application_id);
            
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $application->get_name()));
        }
        if ($this->has_right_id() && $this->has_application_id())
        {
            $right = \application\atlantis\application\right\DataManager :: retrieve(\application\atlantis\application\right\Right :: class_name(), (int) $this->right_id);
            
            // BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null,
            // $right->get_application()->get_name()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $right->get_name()));
        }
        if ($this->has_role_id())
        {
            $role = \application\atlantis\role\DataManager :: retrieve(\application\atlantis\role\Role :: class_name(), (int) $this->role_id);
            
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $role->get_name()));
        }
    
    }

}
?>