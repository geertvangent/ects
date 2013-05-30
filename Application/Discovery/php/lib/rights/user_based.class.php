<?php
namespace application\discovery;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\InCondition;
use common\libraries\OrCondition;
use common\libraries\Session;
use Exception;
use rights\NewUserEntity;
use rights\NewPlatformGroupEntity;
use common\libraries\DataClassCountParameters;
use common\libraries\PropertyConditionVariable;
use common\libraries\StaticConditionVariable;

class UserBasedRights
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
            $current_user = \user\DataManager :: retrieve_by_id(
                \user\User :: class_name(),
                (int) Session :: get_user_id());

            if ($current_user->is_platform_admin())
            {
                return true;
            }

            $user = \user\DataManager :: retrieve_by_id(\user\User :: class_name(), (int) $parameters->get_user_id());

            $user_group_ids = $user->get_groups(true);
            $current_user_group_ids = $current_user->get_groups(true);

            $conditions = array();
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_MODULE_ID),
                new StaticConditionVariable($module_instance_id));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_RIGHT_ID),
                new StaticConditionVariable($right));
            $conditions[] = new InCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_GROUP_ID),
                $user_group_ids);

            $entities_conditions = array();

            $user_entity_conditions = array();
            $user_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_ENTITY_ID),
                new StaticConditionVariable(Session :: get_user_id()));
            $user_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE),
                new StaticConditionVariable(NewUserEntity :: ENTITY_TYPE));
            $entities_conditions[] = new AndCondition($user_entity_conditions);

            $group_entity_conditions = array();
            $group_entity_conditions[] = new InCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_ENTITY_ID),
                $current_user_group_ids);
            $group_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE),
                new StaticConditionVariable(NewPlatformGroupEntity :: ENTITY_TYPE));
            $entities_conditions[] = new AndCondition($group_entity_conditions);

            $conditions[] = new OrCondition($entities_conditions);
            $condition = new AndCondition($conditions);

            $count = DataManager :: count(
                RightsGroupEntityRight :: class_name(),
                new DataClassCountParameters($condition));

            if ($count > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        catch (Exception $exception)
        {
            return false;
        }
    }
}
