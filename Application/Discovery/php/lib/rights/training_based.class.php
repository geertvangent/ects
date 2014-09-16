<?php
namespace application\discovery;

use libraries\AndCondition;
use libraries\EqualityCondition;
use libraries\InCondition;
use libraries\OrCondition;
use libraries\Session;
use application\discovery\RightsGroupEntityRight;
use Exception;
use libraries\DataClassRetrievesParameters;
use libraries\DataClassCountParameters;
use core\rights\NewUserEntity;
use core\rights\NewPlatformGroupEntity;
use libraries\PropertyConditionVariable;
use libraries\StaticConditionVariable;

abstract class TrainingBasedRights
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
            $current_user = \core\user\DataManager :: retrieve_by_id(
                \core\user\User :: class_name(),
                (int) Session :: get_user_id());

            if ($current_user->is_platform_admin())
            {
                return true;
            }

            $context = static :: get_context($module_instance_id, $parameters);

            $codes = array();
            $codes[] = 'DEP_' . $context->get_faculty_id();
            $codes[] = 'TRA_OP_' . $context->get_training_id();
            $codes[] = 'TRA_STU_' . $context->get_training_id();
            $condition = new InCondition(\core\group\Group :: PROPERTY_CODE, $codes);

            $groups = \core\group\DataManager :: retrieves(
                \core\group\Group :: class_name(),
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
                $conditions[] = new InCondition(RightsGroupEntityRight :: PROPERTY_GROUP_ID, $group_ids);

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
                    RightsGroupEntityRight :: PROPERTY_ENTITY_ID,
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

    /**
     *
     * @param int $module_instance_id
     * @param Parameters $parameters
     * @return TrainingBasedContext
     */
    public abstract function get_context($module_instance_id, $parameters);
}
