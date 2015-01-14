<?php
namespace Chamilo\Application\EhbSync\Atlantis\Synchronization;

use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\InequalityCondition;
use Chamilo\Application\Discovery\DataManager;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Application\Discovery\RightsGroupEntityRight;
use Chamilo\Core\Group\Storage\DataClass\Group;

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
                \Chamilo\Application\Atlantis\Application\Application :: class_name(),
                \Chamilo\Application\Atlantis\Application\Application :: PROPERTY_CODE),
            new StaticConditionVariable('DISCOVERY'));

        $parameters = DataClassRetrieveParameters :: generate($condition);

        $application = \Chamilo\Application\Atlantis\Application\DataManager :: retrieve(
            \Chamilo\Application\Atlantis\Application\Application :: class_name(),
            $parameters);

        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                \Chamilo\Application\Atlantis\Application\Right\Right :: class_name(),
                \Chamilo\Application\Atlantis\Application\Right\Right :: PROPERTY_APPLICATION_ID),
            new StaticConditionVariable($application->get_id()));

        $parameters = DataClassRetrievesParameters :: generate($condition);

        $rights = \Chamilo\Application\Atlantis\Application\Right\DataManager :: retrieves(
            \Chamilo\Application\Atlantis\Application\Right\Right :: class_name(),
            $parameters);

        while ($right = $rights->next_result())
        {
            $condition = new EqualityCondition(
                new PropertyConditionVariable(
                    \Chamilo\Application\Discovery\Instance\Instance :: class_name(),
                    \Chamilo\Application\Discovery\Instance\Instance :: PROPERTY_TYPE),
                new StaticConditionVariable($right->get_code()));

            $module_instance = \Chamilo\Application\Discovery\Instance\DataManager :: retrieve(
                \Chamilo\Application\Discovery\Instance\Instance :: class_name(),
                new DataClassRetrieveParameters($condition));

            if ($module_instance instanceof \Chamilo\Application\Discovery\Instance\Instance)
            {
                $condition = new EqualityCondition(
                    new PropertyConditionVariable(
                        \Chamilo\Application\Atlantis\Role\Entitlement\Entitlement :: class_name(),
                        \Chamilo\Application\Atlantis\Role\Entitlement\Entitlement :: PROPERTY_RIGHT_ID),
                    new StaticConditionVariable($right->get_id()));

                $parameters = DataClassRetrievesParameters :: generate($condition);

                $entitlements = \Chamilo\Application\Atlantis\Role\Entitlement\DataManager :: retrieves(
                    \Chamilo\Application\Atlantis\Role\Entitlement\Entitlement :: class_name(),
                    $parameters);

                while ($entitlement = $entitlements->next_result())
                {
                    $conditions = array();
                    $conditions[] = new EqualityCondition(
                        new PropertyConditionVariable(
                            \Chamilo\Application\Atlantis\Role\Entity\RoleEntity :: class_name(),
                            \Chamilo\Application\Atlantis\Role\Entity\RoleEntity :: PROPERTY_ROLE_ID),
                        new StaticConditionVariable($entitlement->get_role_id()));

                    $conditions[] = new InequalityCondition(
                        new PropertyConditionVariable(
                            \Chamilo\Application\Atlantis\Role\Entity\RoleEntity :: class_name(),
                            \Chamilo\Application\Atlantis\Role\Entity\RoleEntity :: PROPERTY_START_DATE),
                        InequalityCondition :: LESS_THAN_OR_EQUAL,
                        new StaticConditionVariable(time()));

                    $conditions[] = new InequalityCondition(
                        new PropertyConditionVariable(
                            \Chamilo\Application\Atlantis\Role\Entity\RoleEntity :: class_name(),
                            \Chamilo\Application\Atlantis\Role\Entity\RoleEntity :: PROPERTY_END_DATE),
                        InequalityCondition :: GREATER_THAN_OR_EQUAL,
                        new StaticConditionVariable(time()));

                    $condition = new AndCondition($conditions);
                    $parameters = DataClassRetrievesParameters :: generate($condition);

                    $entities = \Chamilo\Application\Atlantis\Role\Entity\DataManager :: retrieves(
                        \Chamilo\Application\Atlantis\Role\Entity\RoleEntity :: class_name(),
                        $parameters);

                    while ($entity = $entities->next_result())
                    {
                        $group = \Chamilo\Core\Group\Storage\DataManager :: retrieve_by_id(
                            Group :: class_name(),
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
            $entity_right = new \Chamilo\Application\Discovery\RightsGroupEntityRight();
            $entity_right->set_entity_id($entity[2]);
            $entity_right->set_entity_type($entity[1]);
            $entity_right->set_right_id(1);
            $entity_right->set_module_id($entity[0]);
            $entity_right->set_group_id($entity[3]);

            $entity_right->create();
        }
    }
}