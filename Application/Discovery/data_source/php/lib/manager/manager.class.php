<?php
namespace application\discovery\data_source;

use libraries\Application;

class Manager extends Application
{
    const PARAM_ACTION = 'action';
    const PARAM_TYPE = 'type';
    const PARAM_CONTENT_TYPE = 'content_type';
    const PARAM_DATA_SOURCE_ID = 'data_source_id';
    const ACTION_BROWSE_INSTANCES = 'browser';
    const ACTION_ACTIVATE_INSTANCE = 'activator';
    const ACTION_DEACTIVATE_INSTANCE = 'deactivator';
    const ACTION_UPDATE_INSTANCE = 'updater';
    const ACTION_DELETE_INSTANCE = 'deleter';
    const ACTION_CREATE_INSTANCE = 'creator';
    const ACTION_MOVE_INSTANCE = 'mover';
    const DEFAULT_ACTION = self :: ACTION_BROWSE_INSTANCES;

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
}
