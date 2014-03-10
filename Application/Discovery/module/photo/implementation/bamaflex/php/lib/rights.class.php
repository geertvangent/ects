<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\InCondition;
use common\libraries\OrCondition;
use common\libraries\Session;
use application\discovery\RightsGroupEntityRight;
use Exception;
use rights\NewUserEntity;
use rights\NewPlatformGroupEntity;
use common\libraries\DataClassRetrievesParameters;

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
            $current_user = \user\DataManager :: retrieve_by_id(
                \user\User :: class_name(),
                (int) Session :: get_user_id());

            if ($current_user->is_platform_admin())
            {
                return true;
            }

            $codes = array();

            if ($parameters->get_faculty_id())
            {
                $codes[] = 'DEP_' . $parameters->get_faculty_id();
            }
            elseif ($parameters->get_training_id())
            {
                $module_instance = \application\discovery\instance\DataManager :: retrieve_by_id(
                    \application\discovery\instance\Instance :: class_name(),
                    (int) $module_instance_id);
                $training = \application\discovery\module\photo\DataManager :: get_instance($module_instance)->retrieve_training(
                    $parameters->get_training_id());

                $codes[] = 'DEP_' . $training->get_faculty_id();
                $codes[] = 'TRA_OP_' . $training->get_id();
                $codes[] = 'TRA_STU_' . $training->get_id();
            }
            elseif ($parameters->get_programme_id())
            {
                $module_instance = \application\discovery\instance\DataManager :: retrieve_by_id(
                    \application\discovery\instance\Instance :: class_name(),
                    (int) $module_instance_id);
                $course = \application\discovery\module\photo\DataManager :: get_instance($module_instance)->retrieve_programme(
                    $parameters->get_programme_id());

                $codes[] = 'DEP_' . $course->get_faculty_id();
                $codes[] = 'TRA_OP_' . $course->get_training_id();
                $codes[] = 'TRA_STU_' . $course->get_training_id();
            }
            else
            {
                return false;
            }

            $condition = new InCondition(\group\Group :: PROPERTY_CODE, $codes);

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
}