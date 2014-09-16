<?php
namespace application\atlantis\user_group;

use libraries\Application;

class Manager extends Application
{
    const PARAM_ACTION = 'context_action';
    const ACTION_BROWSE = 'browser';
    const ACTION_VIEW = 'viewer';
    const ACTION_DELETE = 'deleter';
    const ACTION_EDIT = 'editor';
    const ACTION_CREATE = 'creator';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_CONTEXT_ID = 'context_id';

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
