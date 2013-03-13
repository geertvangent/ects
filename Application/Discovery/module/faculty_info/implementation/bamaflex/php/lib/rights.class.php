<?php
namespace application\discovery\module\faculty_info\implementation\bamaflex;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use common\libraries\InCondition;
use common\libraries\OrCondition;
use common\libraries\Translation;
use common\libraries\Session;
use application\discovery\RightsGroupEntityRight;
use user\UserDataManager;
use rights\RightsUtil;
use Exception;

class Rights extends RightsUtil
{
    const VIEW_RIGHT = '1';
    const TYPE_FACULTY = 1;

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

    public function module_is_allowed($right, $entities, $module_instance_id, $parameters)
    {
        try
        {
            $user = UserDataManager :: get_instance()->retrieve_user($parameters->get_user_id());
            $current_user = UserDataManager :: get_instance()->retrieve_user(Session :: get_user_id());
            
            $user_group_ids = $user->get_groups(true);
            $current_user_group_ids = $current_user->get_groups(true);
            
            $conditions = array();
            $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_MODULE_ID, $module_instance_id);
            $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_RIGHT_ID, $right);
            $conditions[] = new InCondition(RightsGroupEntityRight :: PROPERTY_GROUP_ID, $user_group_ids);
            
            $entities_conditions = array();
            
            $user_entity_conditions = array();
            $user_entity_conditions[] = new EqualityCondition(
                RightsGroupEntityRight :: PROPERTY_ENTITY_ID, 
                Session :: get_user_id());
            $user_entity_conditions[] = new EqualityCondition(
                RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE, 
                RightsUserEntity :: ENTITY_TYPE);
            $entities_conditions[] = new AndCondition($user_entity_conditions);
            
            $group_entity_conditions = array();
            $group_entity_conditions[] = new InCondition(
                RightsGroupEntityRight :: PROPERTY_ENTITY_ID, 
                $current_user_group_ids);
            $group_entity_conditions[] = new EqualityCondition(
                RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE, 
                RightsPlatformGroupEntity :: ENTITY_TYPE);
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
                    self :: TYPE_FACULTY, 
                    0, 
                    self :: TREE_TYPE_ROOT);
            }
        }
        catch (Exception $exception)
        {
            return false;
        }
    }

    public function get_module_location_by_identifier($module_instance_id, $parameters)
    {
        return parent :: get_location_by_identifier(
            'discovery_' . $module_instance_id, 
            self :: TYPE_FACULTY, 
            $parameters->get_user_id(), 
            0, 
            self :: TREE_TYPE_ROOT);
    }

    public function get_module_location_id_by_identifier($module_instance_id, $parameters)
    {
        return parent :: get_location_id_by_identifier(
            'discovery_' . $module_instance_id, 
            self :: TYPE_FACULTY, 
            $parameters->get_user_id(), 
            0, 
            self :: TREE_TYPE_ROOT);
    }

    public function create_module_location($module_instance_id, $parameters, $parent)
    {
        return parent :: create_location(
            'discovery_' . $module_instance_id, 
            self :: TYPE_FACULTY, 
            $parameters->get_user_id(), 
            1, 
            $parent, 
            0, 
            0, 
            self :: TREE_TYPE_ROOT);
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
            self :: TYPE_FACULTY);
    }
}
