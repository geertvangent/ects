<?php
namespace application\atlantis\role\entitlement;

use common\libraries\Breadcrumb;

use common\libraries\BreadcrumbTrail;

use common\libraries\DelegateComponent;
use common\libraries\Request;
use common\libraries\EqualityCondition;
use common\libraries\NewObjectTableSupport;

class BrowserComponent extends Manager implements NewObjectTableSupport, DelegateComponent
{
    private $right_id;

    public function get_object_table_condition($object_table_class_name)
    {
        return new EqualityCondition(Entitlement :: PROPERTY_RIGHT_ID, $this->right_id);
    }

    function run()
    {
        $this->right_id = Request :: get(\application\atlantis\application\right\Manager :: PARAM_RIGHT_ID);
        $this->add_breadcrumb();
        
        $this->display_header();
        $table = new EntitlementTable($this);
        echo ($table->as_html());
        $this->display_footer();
    }

    function add_breadcrumb()
    {
        $right = \application\atlantis\application\right\DataManager :: retrieve(\application\atlantis\application\right\Right :: class_name(), (int) $this->right_id);
        
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $right->get_application()->get_name()));
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $right->get_name()));
    }

}
?>