<?php
namespace Chamilo\Application\Discovery\Module\Elo\Implementation\Chamilo;

use Chamilo\Libraries\Storage\AndCondition;
use Chamilo\Libraries\Storage\EqualityCondition;
use Chamilo\Libraries\Storage\InCondition;
use Chamilo\Libraries\Storage\OrCondition;
use Chamilo\Libraries\Platform\Session;
use Chamilo\Application\Discovery\RightsGroupEntityRight;
use Chamilo\Exception;
use Chamilo\Core\Rights\UserEntity;
use Chamilo\Core\Rights\PlatformGroupEntity;
use Chamilo\Libraries\Storage\PropertyConditionVariable;
use Chamilo\Libraries\Storage\StaticConditionVariable;

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
            $user = \Chamilo\Core\User\DataManager :: retrieve_by_id(
                \Chamilo\Core\User\User :: class_name(),
                (int) $parameters->get_user_id());
            $current_user = \Chamilo\Core\User\DataManager :: retrieve_by_id(
                \Chamilo\Core\User\User :: class_name(),
                (int) Session :: get_user_id());

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
                new StaticConditionVariable(UserEntity :: ENTITY_TYPE));
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
                new StaticConditionVariable(PlatformGroupEntity :: ENTITY_TYPE));
            $entities_conditions[] = new AndCondition($group_entity_conditions);

            $conditions[] = new OrCondition($entities_conditions);
            $condition = new AndCondition($conditions);

            $count = \Chamilo\Application\Discovery\DataManager :: get_instance()->count_rights_group_entity_rights($condition);

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
