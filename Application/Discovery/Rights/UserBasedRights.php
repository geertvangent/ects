<?php
namespace Ehb\Application\Discovery\Rights;

use Chamilo\Core\Rights\Entity\PlatformGroupEntity;
use Chamilo\Core\Rights\Entity\UserEntity;
use Chamilo\Libraries\Platform\Session\Session;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Discovery\RightsGroupEntityRight;
use Ehb\Application\Discovery\Storage\DataManager;

class UserBasedRights
{
    const VIEW_RIGHT = '1';

    public static function is_visible($module_instance_id, $parameters)
    {
        return self::is_allowed(self::VIEW_RIGHT, $module_instance_id, $parameters);
    }

    public static function is_allowed($right, $module_instance_id, $parameters)
    {
        try
        {
            $current_user = \Chamilo\Core\User\Storage\DataManager::retrieve_by_id(
                \Chamilo\Core\User\Storage\DataClass\User::class_name(), 
                (int) Session::get_user_id());
            
            if ($current_user->is_platform_admin())
            {
                return true;
            }
            
            $user = \Chamilo\Core\User\Storage\DataManager::retrieve_by_id(
                \Chamilo\Core\User\Storage\DataClass\User::class_name(), 
                (int) $parameters->get_user_id());
            
            $user_group_ids = $user->get_groups(true);
            $current_user_group_ids = $current_user->get_groups(true);
            
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_MODULE_ID), 
                new StaticConditionVariable($module_instance_id));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_RIGHT_ID), 
                new StaticConditionVariable($right));
            $conditions[] = new InCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_GROUP_ID), 
                $user_group_ids);
            
            $entities_conditions = array();
            
            $user_entity_conditions = array();
            $user_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_ENTITY_ID), 
                new StaticConditionVariable(Session::get_user_id()));
            $user_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_ENTITY_TYPE), 
                new StaticConditionVariable(UserEntity::ENTITY_TYPE));
            $entities_conditions[] = new AndCondition($user_entity_conditions);
            
            $group_entity_conditions = array();
            $group_entity_conditions[] = new InCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_ENTITY_ID), 
                $current_user_group_ids);
            $group_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_ENTITY_TYPE), 
                new StaticConditionVariable(PlatformGroupEntity::ENTITY_TYPE));
            $entities_conditions[] = new AndCondition($group_entity_conditions);
            
            $conditions[] = new OrCondition($entities_conditions);
            $condition = new AndCondition($conditions);
            
            $count = DataManager::count(RightsGroupEntityRight::class_name(), new DataClassCountParameters($condition));
            
            if ($count > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        catch (\Exception $exception)
        {
            return false;
        }
    }
}
