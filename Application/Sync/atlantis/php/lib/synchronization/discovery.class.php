<?php
namespace application\ehb_sync\atlantis;

use common\libraries\AndCondition;
use common\libraries\InequalityCondition;
use application\discovery\ModuleInstance;
use group\GroupDataManager;
use group\Group;
use common\libraries\InCondition;
use application\discovery\DataManager;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\EqualityCondition;
use common\libraries\DataClassRetrieveParameters;

class DiscoverySynchronization
{

    function run()
    {
        $old_entity_rights = DataManager :: get_instance()->retrieve_rights_group_entity_rights();
        $new_entity_right_cache = array();
        $old_entity_right_cache = array();
        
        while ($old_entity_right = $old_entity_rights->next_result())
        {
            $old_entity_right_cache[$old_entity_right->get_id()] = $old_entity_right->get_string();
        }
        
        $condition = new EqualityCondition(\application\atlantis\application\Application :: PROPERTY_CODE, 'DISCOVERY');
        $parameters = DataClassRetrieveParameters :: generate($condition);
        $application = \application\atlantis\application\DataManager :: retrieve(
                \application\atlantis\application\Application :: class_name(), $parameters);
        
        $condition = new EqualityCondition(\application\atlantis\application\right\Right :: PROPERTY_APPLICATION_ID, 
                $application->get_id());
        $parameters = DataClassRetrievesParameters :: generate($condition);
        $rights = \application\atlantis\application\right\DataManager :: retrieves(
                \application\atlantis\application\right\Right :: class_name(), $parameters);
        
        while ($right = $rights->next_result())
        {
            $condition = new EqualityCondition(\application\discovery\ModuleInstance :: PROPERTY_TYPE, 
                    $right->get_code());
            $module_instance = DataManager :: get_instance()->retrieve_module_instances($condition)->next_result();
            if ($module_instance instanceof ModuleInstance)
            {
                $condition = new EqualityCondition(
                        \application\atlantis\role\entitlement\Entitlement :: PROPERTY_RIGHT_ID, $right->get_id());
                $parameters = DataClassRetrievesParameters :: generate($condition);
                $entitlements = \application\atlantis\role\entitlement\DataManager :: retrieves(
                        \application\atlantis\role\entitlement\Entitlement :: class_name(), $parameters);
                
                while ($entitlement = $entitlements->next_result())
                {
                    $conditions = array();
                    $conditions[] = new EqualityCondition(
                            \application\atlantis\role\entity\RoleEntity :: PROPERTY_ROLE_ID, 
                            $entitlement->get_role_id());
                    $conditions[] = new InequalityCondition(
                            \application\atlantis\role\entity\RoleEntity :: PROPERTY_START_DATE, 
                            InequalityCondition :: LESS_THAN_OR_EQUAL, time());
                    $conditions[] = new InequalityCondition(
                            \application\atlantis\role\entity\RoleEntity :: PROPERTY_END_DATE, 
                            InequalityCondition :: GREATER_THAN_OR_EQUAL, time());
                    $condition = new AndCondition($conditions);
                    
                    $parameters = DataClassRetrievesParameters :: generate($condition);
                    $entities = \application\atlantis\role\entity\DataManager :: retrieves(
                            \application\atlantis\role\entity\RoleEntity :: class_name(), $parameters);
                    
                    while ($entity = $entities->next_result())
                    {
                        $context = $entity->get_context();
                        $codes = array();
                        switch ($context->get_context_type())
                        {
                            case 0 :
                                $codes[] = 'CA';
                                break;
                            case 1 :
                                $codes[] = 'AY_' . $context->get_context_name();
                                break;
                            case 2 :
                                $codes[] = 'DEP_' . $context->get_context_id();
                                break;
                            case 3 :
                                $codes[] = 'TRA_OP_' . $context->get_context_id();
                                $codes[] = 'TRA_STU_' . $context->get_context_id();
                                break;
                            case 4 :
                                $codes[] = 'COU_OP_' . $context->get_context_id();
                                $codes[] = 'COU_STU_' . $context->get_context_id();
                                break;
                        }
                        
                        $condition = new InCondition(Group :: PROPERTY_CODE, $codes);
                        $groups = GroupDataManager :: get_instance()->retrieve_groups($condition);
                        
                        while ($group = $groups->next_result())
                        {
                            $new_entity_right_cache[] = $module_instance->get_id() . '_' . $entity->get_entity_type() . '_' . $entity->get_entity_id() . '_' . $group->get_id();
                        }
                    }
                }
            }
        }
        
        $to_delete = array_diff($old_entity_right_cache, $new_entity_right_cache);
        $to_add = array_diff($new_entity_right_cache, $old_entity_right_cache);
        
        foreach ($to_delete as $entity_right_id => $entity)
        {
            DataManager :: get_instance()->delete_rights_group_entity_rights($entity_right_id);
        }
        
        foreach ($to_add as $key => $entity)
        {
            $entity = explode('_', $entity);
            $entity_right = new \application\discovery\RightsGroupEntityRight();
            $entity_right->set_entity_id($entity[2]);
            $entity_right->set_entity_type($entity[1]);
            $entity_right->set_right_id(1);
            $entity_right->set_module_id($entity[0]);
            $entity_right->set_group_id($entity[3]);
            
            $entity_right->create();
        }
    }
}