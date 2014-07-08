<?php
namespace application\ehb_sync\atlantis;

use common\libraries\AndCondition;
use common\libraries\InequalityCondition;
use application\discovery\DataManager;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\EqualityCondition;
use common\libraries\DataClassRetrieveParameters;
use common\libraries\PropertyConditionVariable;
use common\libraries\StaticConditionVariable;
use application\discovery\RightsGroupEntityRight;

class DiscoverySynchronization
{

    function run()
    {
        $old_entity_rights = DataManager :: retrieves(RightsGroupEntityRight :: class_name());
        $new_entity_right_cache = array();
        $old_entity_right_cache = array();

        while ($old_entity_right = $old_entity_rights->next_result())
        {
            $old_entity_right_cache[$old_entity_right->get_id()] = $old_entity_right->get_string();
        }

        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                \application\atlantis\application\Application :: class_name(),
                \application\atlantis\application\Application :: PROPERTY_CODE),
            new StaticConditionVariable('DISCOVERY'));

        $parameters = DataClassRetrieveParameters :: generate($condition);

        $application = \application\atlantis\application\DataManager :: retrieve(
            \application\atlantis\application\Application :: class_name(),
            $parameters);

        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                \application\atlantis\application\right\Right :: class_name(),
                \application\atlantis\application\right\Right :: PROPERTY_APPLICATION_ID),
            new StaticConditionVariable($application->get_id()));

        $parameters = DataClassRetrievesParameters :: generate($condition);

        $rights = \application\atlantis\application\right\DataManager :: retrieves(
            \application\atlantis\application\right\Right :: class_name(),
            $parameters);

        while ($right = $rights->next_result())
        {
            $condition = new EqualityCondition(
                new PropertyConditionVariable(
                    \application\discovery\instance\Instance :: class_name(),
                    \application\discovery\instance\Instance :: PROPERTY_TYPE),
                new StaticConditionVariable($right->get_code()));

            $module_instance = \application\discovery\instance\DataManager :: retrieve(
                \application\discovery\instance\Instance :: class_name(),
                new DataClassRetrieveParameters($condition));

            if ($module_instance instanceof \application\discovery\instance\Instance)
            {
                $condition = new EqualityCondition(
                    new PropertyConditionVariable(
                        \application\atlantis\role\entitlement\Entitlement :: class_name(),
                        \application\atlantis\role\entitlement\Entitlement :: PROPERTY_RIGHT_ID),
                    new StaticConditionVariable($right->get_id()));

                $parameters = DataClassRetrievesParameters :: generate($condition);

                $entitlements = \application\atlantis\role\entitlement\DataManager :: retrieves(
                    \application\atlantis\role\entitlement\Entitlement :: class_name(),
                    $parameters);

                while ($entitlement = $entitlements->next_result())
                {
                    $conditions = array();
                    $conditions[] = new EqualityCondition(
                        new PropertyConditionVariable(
                            \application\atlantis\role\entity\RoleEntity :: class_name(),
                            \application\atlantis\role\entity\RoleEntity :: PROPERTY_ROLE_ID),
                        new StaticConditionVariable($entitlement->get_role_id()));

                    $conditions[] = new InequalityCondition(
                        new PropertyConditionVariable(
                            \application\atlantis\role\entity\RoleEntity :: class_name(),
                            \application\atlantis\role\entity\RoleEntity :: PROPERTY_START_DATE),
                        InequalityCondition :: LESS_THAN_OR_EQUAL,
                        new StaticConditionVariable(time()));

                    $conditions[] = new InequalityCondition(
                        new PropertyConditionVariable(
                            \application\atlantis\role\entity\RoleEntity :: class_name(),
                            \application\atlantis\role\entity\RoleEntity :: PROPERTY_END_DATE),
                        InequalityCondition :: GREATER_THAN_OR_EQUAL,
                        new StaticConditionVariable(time()));

                    $condition = new AndCondition($conditions);
                    $parameters = DataClassRetrievesParameters :: generate($condition);

                    $entities = \application\atlantis\role\entity\DataManager :: retrieves(
                        \application\atlantis\role\entity\RoleEntity :: class_name(),
                        $parameters);

                    while ($entity = $entities->next_result())
                    {
                        $group = \group\DataManager :: retrieve_by_id(
                            \group\Group :: class_name(),
                            $entity->get_context()->get_id());

                        $new_entity_right_cache[] = $module_instance->get_id() . '_' . $entity->get_entity_type() . '_' .
                             $entity->get_entity_id() . '_' . $group->get_id();
                    }
                }
            }
        }

        $to_delete = array_diff($old_entity_right_cache, $new_entity_right_cache);
        $to_add = array_diff($new_entity_right_cache, $old_entity_right_cache);

        foreach ($to_delete as $entity_right_id => $entity)
        {
            $condition = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(),
                    RightsGroupEntityRight :: PROPERTY_ID),
                new StaticConditionVariable($entity_right_id));

            DataManager :: deletes(RightsGroupEntityRight :: class_name(), $condition);
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