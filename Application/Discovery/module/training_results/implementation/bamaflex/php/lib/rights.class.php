<?php
namespace application\discovery\module\training_results\implementation\bamaflex;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\InCondition;
use common\libraries\OrCondition;
use common\libraries\Session;
use application\discovery\module\training_results\DataManager;
use application\discovery\RightsGroupEntityRight;
use Exception;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\PropertyConditionVariable;
use rights\NewUserEntity;
use rights\NewPlatformGroupEntity;

class Rights
{
    const VIEW_RIGHT = '1';

    public static function is_visible($module_instance_id, $parameters)
    {
        return self :: is_allowed(self :: VIEW_RIGHT, $module_instance_id, $parameters);
    }

    public static function is_allowed($right, $module_instance_id, $parameters)
    {
        try
        {
            $parameter = new \application\discovery\module\training_info\implementation\bamaflex\Parameters();
            $parameter->set_training_id($parameters->get_training_id());
            $parameter->set_source($parameters->get_source());
            
            $module_instance = \application\discovery\instance\DataManager :: retrieve_by_id(
                \application\discovery\instance\Instance :: class_name(), 
                (int) $module_instance_id);
            $training = DataManager :: get_instance($module_instance)->retrieve_training($parameter);
            
            $current_user = \user\DataManager :: retrieve_by_id(
                \user\User :: class_name(), 
                (int) Session :: get_user_id());
            
            $codes = array();
            $codes[] = 'DEP_' . $training->get_faculty_id();
            $codes[] = 'TRA_OP_' . $training->get_id();
            $codes[] = 'TRA_STU_' . $training->get_id();
            $condition = new InCondition(
                new PropertyConditionVariable(\group\Group :: class_name(), \group\Group :: PROPERTY_CODE), 
                $codes);
            
            $groups = \group\DataManager :: retrieves(
                \group\Group :: class_name(), 
                new DataClassRetrievesParameters($condition));
            
            if ($groups->size() > 0)
            {
                $group_ids = array();
                while ($group = $groups->next_result())
                {
                    $group_ids[] = $group->get_id();
                }
                $current_user_group_ids = $current_user->get_groups(true);
                
                $conditions = array();
                $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_MODULE_ID, $module_instance_id);
                $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_RIGHT_ID, $right);
                $conditions[] = new InCondition(RightsGroupEntityRight :: PROPERTY_GROUP_ID, $group_ids);
                
                $entities_conditions = array();
                
                $user_entity_conditions = array();
                $user_entity_conditions[] = new EqualityCondition(
                    RightsGroupEntityRight :: PROPERTY_ENTITY_ID, 
                    Session :: get_user_id());
                $user_entity_conditions[] = new EqualityCondition(
                    RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE, 
                    NewUserEntity :: ENTITY_TYPE);
                $entities_conditions[] = new AndCondition($user_entity_conditions);
                
                $group_entity_conditions = array();
                $group_entity_conditions[] = new InCondition(
                    RightsGroupEntityRight :: PROPERTY_ENTITY_ID, 
                    $current_user_group_ids);
                $group_entity_conditions[] = new EqualityCondition(
                    RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE, 
                    NewPlatformGroupEntity :: ENTITY_TYPE);
                $entities_conditions[] = new AndCondition($group_entity_conditions);
                
                $conditions[] = new OrCondition($entities_conditions);
                $condition = new AndCondition($conditions);
                
                $count = \application\discovery\DataManager :: get_instance()->count_rights_group_entity_rights(
                    $condition);
                
                if ($count > 0)
                {
                    return true;
                }
                else
                {
                    if ($current_user->is_platform_admin())
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
            {
                if ($current_user->is_platform_admin())
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        catch (Exception $exception)
        {
            return false;
        }
    }
}
