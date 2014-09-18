<?php
namespace application\discovery\module\elo\implementation\chamilo;

use libraries\AndCondition;
use libraries\EqualityCondition;
use libraries\InCondition;
use libraries\OrCondition;
use libraries\Session;
use application\discovery\RightsGroupEntityRight;
use Exception;
use core\rights\NewUserEntity;
use core\rights\NewPlatformGroupEntity;
use libraries\PropertyConditionVariable;

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
            $user = \core\user\DataManager :: retrieve_by_id(
                \core\user\User :: class_name(),
                (int) $parameters->get_user_id());
            $current_user = \core\user\DataManager :: retrieve_by_id(
                \core\user\User :: class_name(),
                (int) Session :: get_user_id());

            $user_group_ids = $user->get_groups(true);
            $current_user_group_ids = $current_user->get_groups(true);

            $conditions = array();
            $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_MODULE_ID, $module_instance_id);
            $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_RIGHT_ID, $right);
            $conditions[] = new InCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_GROUP_ID),
                $user_group_ids);

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
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_ENTITY_ID),
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
