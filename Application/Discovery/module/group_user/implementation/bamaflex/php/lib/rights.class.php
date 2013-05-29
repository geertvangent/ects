<?php
namespace application\discovery\module\group_user\implementation\bamaflex;

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

    public static function is_allowed($right, $rendition_implementation)
    {
        try
        {
            $module_instance_id = $rendition_implementation->get_module_instance()->get_id();
            $parameters = $rendition_implementation->get_module_parameters();
            
            $group = \application\discovery\module\group_user\DataManager :: get_instance(
                $rendition_implementation->get_module_instance())->retrieve_group(Module :: get_group_parameters());
            
            $group_code = 'TRA_STU_' . $group->get_training_id();
            
            $group = \group\DataManager :: retrieve_group_by_code($group_code);
            
            $group_ids = array();
            
            if ($group instanceof \group\Group)
            {
                
                foreach ($group->get_parents() as $parent)
                {
                    $group_ids[] = $parent->get_id();
                }
            }
            $current_user = \user\DataManager :: retrieve_by_id(
                \user\User :: class_name(), 
                (int) Session :: get_user_id());
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
