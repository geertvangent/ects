<?php
namespace application\discovery\module\group\implementation\bamaflex;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\InCondition;
use common\libraries\OrCondition;
use common\libraries\Session;
use application\discovery\RightsGroupEntityRight;
use Exception;
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
            $user = \user\DataManager :: retrieve_by_id(\user\User :: class_name(), (int) $parameters->get_user_id());
            $current_user = \user\DataManager :: retrieve_by_id(
                \user\User :: class_name(), 
                (int) Session :: get_user_id());
            
            $user_group_ids = $user->get_groups(true);
            $current_user_group_ids = $current_user->get_groups(true);
            
            $conditions = array();
            $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_MODULE_ID, $module_instance_id);
            $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_RIGHT_ID, $right);
            $conditions[] = new InCondition(RightsGroupEntityRight :: PROPERTY_GROUP_ID, $user_group_ids);
            
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
            
            $count = \application\discovery\DataManager :: get_instance()->count_rights_group_entity_rights($condition);
            
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
        catch (Exception $exception)
        {
            return false;
        }
    }
}
