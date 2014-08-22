<?php
namespace application\atlantis\role\entity;

use libraries\SubManager;

class Manager extends SubManager
{
    const PARAM_ACTION = 'role_entity_action';
    const ACTION_BROWSE = 'browser';
    const ACTION_DELETE = 'deleter';
    const ACTION_CREATE = 'creator';
    const ACTION_ENTITY_TYPE = 'entity_type';
    const DEFAULT_ACTION = self :: ACTION_CREATE;
    const PARAM_ROLE_ENTITY_ID = 'role_entity_id';
    const PARAM_ENTITY_TYPE = 'entity_type';
    const PARAM_ENTITY_ID = 'entity_id';
    const PARAM_START_DATE = 'start_date';
    const PARAM_END_DATE = 'end_date';

    public static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }

    /**
     * Helper function for the Application class, pending access to class constants via variables in PHP 5.3 e.g. $name
     * = $class :: DEFAULT_ACTION DO NOT USE IN THIS APPLICATION'S CONTEXT Instead use: - self :: DEFAULT_ACTION in the
     * context of this class - YourApplicationManager :: DEFAULT_ACTION in all other application classes
     */
    public function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }

    public static function launch($application)
    {
        parent :: launch(null, $application);
    }
}
