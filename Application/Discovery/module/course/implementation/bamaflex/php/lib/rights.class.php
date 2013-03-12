<?php
namespace application\discovery\module\course\implementation\bamaflex;

use common\libraries\Session;
use rights\RightsUtil;
use common\libraries\Translation;
use Exception;

class Rights extends RightsUtil
{
    const VIEW_RIGHT = '1';
    const TYPE_PROFILE = 1;

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
            if ($parameters->get_user_id() == Session :: get_user_id())
            {
                return true;
            }
            else
            {
                return parent :: is_allowed($right, 'discovery_' . $module_instance_id, null, $entities,
                        $parameters->get_user_id(), self :: TYPE_PROFILE, 0, self :: TREE_TYPE_ROOT);
            }
        }
        catch (Exception $exception)
        {
            return false;
        }
    }

    public function get_module_location_by_identifier($module_instance_id, $parameters)
    {
        return parent :: get_location_by_identifier('discovery_' . $module_instance_id, self :: TYPE_PROFILE,
                $parameters->get_user_id(), 0, self :: TREE_TYPE_ROOT);
    }

    public function get_module_location_id_by_identifier($module_instance_id, $parameters)
    {
        return parent :: get_location_id_by_identifier('discovery_' . $module_instance_id, self :: TYPE_PROFILE,
                $parameters->get_user_id(), 0, self :: TREE_TYPE_ROOT);
    }

    public function create_module_location($module_instance_id, $parameters, $parent)
    {
        return parent :: create_location('discovery_' . $module_instance_id, self :: TYPE_PROFILE,
                $parameters->get_user_id(), 1, $parent, 0, 0, self :: TREE_TYPE_ROOT);
    }

    public function get_module_rights_location_entity_right($module_instance_id, $entity_id, $entity_type, $location_id)
    {
        return parent :: get_rights_location_entity_right('discovery_' . $module_instance_id, self :: VIEW_RIGHT,
                $entity_id, $entity_type, $location_id);
    }

    public function invert_module_location_entity_right($module_instance_id, $right_id, $entity_id, $entity_type, $location_id)
    {
        return parent :: invert_location_entity_right('discovery_' . $module_instance_id, $right_id, $entity_id,
                $entity_type, $location_id);
    }

    public function get_module_targets_entities($module_instance_id, $parameters)
    {
        return parent :: get_target_entities(self :: VIEW_RIGHT, 'discovery_' . $module_instance_id,
                $parameters->get_user_id(), self :: TYPE_PROFILE);
    }
}
