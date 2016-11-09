<?php
namespace Ehb\Application\Sync\Atlantis\Synchronization;

use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InequalityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Discovery\RightsGroupEntityRight;
use Ehb\Application\Discovery\Storage\DataManager;

class DiscoverySynchronization
{

    function run()
    {
        $old_entity_rights = DataManager::retrieves(
            RightsGroupEntityRight::class_name(), 
            new DataClassRetrievesParameters());
        
        $new_entity_right_cache = array();
        $old_entity_right_cache = array();
        
        while ($old_entity_right = $old_entity_rights->next_result())
        {
            $old_entity_right_cache[$old_entity_right->get_id()] = $old_entity_right->get_string();
        }
        
        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                \Ehb\Application\Atlantis\Application\Storage\DataClass\Application::class_name(), 
                \Ehb\Application\Atlantis\Application\Storage\DataClass\Application::PROPERTY_CODE), 
            new StaticConditionVariable('DISCOVERY'));
        
        $parameters = DataClassRetrieveParameters::generate($condition);
        
        $application = \Ehb\Application\Atlantis\Application\Storage\DataManager::retrieve(
            \Ehb\Application\Atlantis\Application\Storage\DataClass\Application::class_name(), 
            $parameters);
        
        $condition = new EqualityCondition(
            new PropertyConditionVariable(
                \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right::class_name(), 
                \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right::PROPERTY_APPLICATION_ID), 
            new StaticConditionVariable($application->get_id()));
        
        $parameters = new DataClassRetrievesParameters($condition);
        
        $rights = \Ehb\Application\Atlantis\Application\Right\Storage\DataManager::retrieves(
            \Ehb\Application\Atlantis\Application\Right\Storage\DataClass\Right::class_name(), 
            $parameters);
        
        while ($right = $rights->next_result())
        {
            $condition = new EqualityCondition(
                new PropertyConditionVariable(
                    \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance::class_name(), 
                    \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance::PROPERTY_TYPE), 
                new StaticConditionVariable($right->get_code()));
            
            $module_instance = \Ehb\Application\Discovery\Instance\Storage\DataManager::retrieve(
                \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance::class_name(), 
                new DataClassRetrieveParameters($condition));
            
            if ($module_instance instanceof \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance)
            {
                $condition = new EqualityCondition(
                    new PropertyConditionVariable(
                        \Ehb\Application\Atlantis\Role\Entitlement\Storage\DataClass\Entitlement::class_name(), 
                        \Ehb\Application\Atlantis\Role\Entitlement\Storage\DataClass\Entitlement::PROPERTY_RIGHT_ID), 
                    new StaticConditionVariable($right->get_id()));
                
                $parameters = new DataClassRetrievesParameters($condition);
                
                $entitlements = \Ehb\Application\Atlantis\Role\Entitlement\Storage\DataManager::retrieves(
                    \Ehb\Application\Atlantis\Role\Entitlement\Storage\DataClass\Entitlement::class_name(), 
                    $parameters);
                
                while ($entitlement = $entitlements->next_result())
                {
                    $conditions = array();
                    $conditions[] = new EqualityCondition(
                        new PropertyConditionVariable(
                            \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity::class_name(), 
                            \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity::PROPERTY_ROLE_ID), 
                        new StaticConditionVariable($entitlement->get_role_id()));
                    
                    $conditions[] = new InequalityCondition(
                        new PropertyConditionVariable(
                            \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity::class_name(), 
                            \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity::PROPERTY_START_DATE), 
                        InequalityCondition::LESS_THAN_OR_EQUAL, 
                        new StaticConditionVariable(time()));
                    
                    $conditions[] = new InequalityCondition(
                        new PropertyConditionVariable(
                            \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity::class_name(), 
                            \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity::PROPERTY_END_DATE), 
                        InequalityCondition::GREATER_THAN_OR_EQUAL, 
                        new StaticConditionVariable(time()));
                    
                    $condition = new AndCondition($conditions);
                    $parameters = new DataClassRetrievesParameters($condition);
                    
                    $entities = \Ehb\Application\Atlantis\Role\Entity\Storage\DataManager::retrieves(
                        \Ehb\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity::class_name(), 
                        $parameters);
                    
                    while ($entity = $entities->next_result())
                    {
                        $group = \Chamilo\Core\Group\Storage\DataManager::retrieve_by_id(
                            Group::class_name(), 
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
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_ID), 
                new StaticConditionVariable($entity_right_id));
            
            DataManager::deletes(RightsGroupEntityRight::class_name(), $condition);
        }
        
        foreach ($to_add as $key => $entity)
        {
            $entity = explode('_', $entity);
            $entity_right = new \Ehb\Application\Discovery\RightsGroupEntityRight();
            $entity_right->set_entity_id($entity[2]);
            $entity_right->set_entity_type($entity[1]);
            $entity_right->set_right_id(1);
            $entity_right->set_module_id($entity[0]);
            $entity_right->set_group_id($entity[3]);
            
            $entity_right->create();
        }
    }
}