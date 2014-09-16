<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

use libraries\AndCondition;
use libraries\EqualityCondition;
use libraries\InCondition;
use libraries\OrCondition;
use libraries\Translation;
use libraries\Session;
use application\discovery\RightsGroupEntityRight;
use Exception;
use core\rights\RightsUtil;
use libraries\PropertyConditionVariable;
use libraries\StaticConditionVariable;

class Rights extends RightsUtil
{
    const VIEW_RIGHT = '1';
    const TYPE_PERSON = 1;

    private static $instance;

    public static function get_instance()
    {
        if (! isset(self :: $instance))
        {
            self :: $instance = new self();
        }
        return self :: $instance;
    }

    public static function get_available_rights()
    {
        return array(Translation :: get('ViewRight') => self :: VIEW_RIGHT);
    }

    public function get_current_location($module_instance_id)
    {
        $parameters = Module :: module_parameters();
        $location = $this->get_module_location_by_identifier($module_instance_id, $parameters);
        if ($location)
        {
            return $location;
        }
        else
        {
            return $this->create_module_location(
                $module_instance_id, 
                $parameters, 
                $this->get_root_id('discovery_' . $module_instance_id), 
                true);
        }
    }

    public function module_is_allowed($right, $entities, $module_instance_id, $parameters)
    {
        try
        {
            $user = \core\user\DataManager :: get_instance()->retrieve_user($parameters->get_user_id());
            $current_user = \core\user\DataManager :: get_instance()->retrieve_user(Session :: get_user_id());
            
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
            $conditions[] = new InCondition(RightsGroupEntityRight :: PROPERTY_GROUP_ID, $user_group_ids);
            
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
                new StaticConditionVariable(RightsUserEntity :: ENTITY_TYPE));
            $entities_conditions[] = new AndCondition($user_entity_conditions);
            
            $group_entity_conditions = array();
            $group_entity_conditions[] = new InCondition(
                RightsGroupEntityRight :: PROPERTY_ENTITY_ID, 
                $current_user_group_ids);
            $group_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight :: class_name(), 
                    RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE), 
                new StaticConditionVariable(RightsPlatformGroupEntity :: ENTITY_TYPE));
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
                return parent :: is_allowed(
                    $right, 
                    'discovery_' . $module_instance_id, 
                    null, 
                    $entities, 
                    $parameters->get_user_id(), 
                    self :: TYPE_PERSON, 
                    0, 
                    self :: TREE_TYPE_ROOT);
            }
        }
        catch (Exception $exception)
        {
            return false;
        }
    }

    public function is_visible($module_instance_id, $parameters)
    {
        $entities = array();
        $entities[RightsUserEntity :: ENTITY_TYPE] = RightsUserEntity :: get_instance();
        $entities[RightsPlatformGroupEntity :: ENTITY_TYPE] = RightsPlatformGroupEntity :: get_instance();
        
        return $this->module_is_allowed(self :: VIEW_RIGHT, $entities, $module_instance_id, $parameters);
    }

    public function get_module_location_by_identifier($module_instance_id, $parameters)
    {
        return parent :: get_location_by_identifier(
            'discovery_' . $module_instance_id, 
            self :: TYPE_PERSON, 
            $parameters->get_user_id(), 
            0, 
            self :: TREE_TYPE_ROOT);
    }

    public function get_module_location_id_by_identifier($module_instance_id, $parameters)
    {
        return parent :: get_location_id_by_identifier(
            'discovery_' . $module_instance_id, 
            self :: TYPE_PERSON, 
            $parameters->get_user_id(), 
            0, 
            self :: TREE_TYPE_ROOT);
    }

    public function create_module_location($module_instance_id, $parameters, $parent, $return_location)
    {
        return parent :: create_location(
            'discovery_' . $module_instance_id, 
            self :: TYPE_PERSON, 
            $parameters->get_user_id(), 
            1, 
            $parent, 
            0, 
            0, 
            self :: TREE_TYPE_ROOT, 
            $return_location);
    }

    public function get_module_root($module_instance_id)
    {
        return parent :: get_root('discovery_' . $module_instance_id);
    }

    public function get_module_rights_location_entity_right($module_instance_id, $entity_id, $entity_type, $location_id)
    {
        return parent :: get_rights_location_entity_right(
            'discovery_' . $module_instance_id, 
            self :: VIEW_RIGHT, 
            $entity_id, 
            $entity_type, 
            $location_id);
    }

    public function invert_module_location_entity_right($module_instance_id, $right_id, $entity_id, $entity_type, 
        $location_id)
    {
        return parent :: invert_location_entity_right(
            'discovery_' . $module_instance_id, 
            $right_id, 
            $entity_id, 
            $entity_type, 
            $location_id);
    }

    public function get_module_targets_entities($module_instance_id, $parameters)
    {
        return parent :: get_target_entities(
            self :: VIEW_RIGHT, 
            'discovery_' . $module_instance_id, 
            $parameters->get_user_id(), 
            self :: TYPE_PERSON);
    }
}
