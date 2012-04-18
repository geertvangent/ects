<?php
namespace application\atlantis\role\entity;

use common\libraries\AndCondition;

use common\libraries\EqualityCondition;

use common\libraries\Request;

use common\libraries\NewObjectTableSupport;

class BrowserComponent extends Manager implements NewObjectTableSupport
{
    
    /*
     * (non-PHPdoc) @see
     * common\libraries.NewObjectTableSupport::get_object_table_condition()
     */
    public function get_object_table_condition($object_table_class_name)
    {
        $entity_type = Request :: get(self :: PARAM_ENTITY_TYPE);
        $entity_id = Request :: get(self :: PARAM_ENTITY_ID);
        $context_id = Request :: get(\application\atlantis\context\Manager :: PARAM_CONTEXT_ID);
        $role_id = Request :: get(\application\atlantis\role\Manager :: PARAM_ROLE_ID);
        
        $conditions = array();
        if ($entity_type && $entity_id)
        {
            $conditions[] = new EqualityCondition(RoleEntity :: PROPERTY_ENTITY_ID, $entity_id);
            $conditions[] = new EqualityCondition(RoleEntity :: PROPERTY_ENTITY_TYPE, $entity_type);
        }
        if ($context_id)
        {
            $conditions[] = new EqualityCondition(RoleEntity :: PROPERTY_CONTEXT_ID, $context_id);
        }
        if ($role_id)
        {
            $conditions[] = new EqualityCondition(RoleEntity :: PROPERTY_ROLE_ID, $role_id);
        }
        if (count($conditions) > 0)
        {
            return new AndCondition($conditions);
        }
        else
            return null;
    }

    /**
     * Runs this component and displays its output.
     */
    function run()
    {
        $this->display_header();
        
        $table = new RoleEntityTable($this);
        echo ($table->as_html());
        
        $this->display_footer();
    }

}
?>