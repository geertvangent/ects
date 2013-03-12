<?php
namespace application\atlantis\application;

use common\libraries\SubManager;

class Manager extends SubManager
{
    const PARAM_ACTION = 'application_action';
    const ACTION_BROWSE = 'browser';
    const ACTION_DELETE = 'deleter';
    const ACTION_EDIT = 'editor';
    const ACTION_RIGHTS = 'rights';
    const ACTION_CREATE = 'creator';
    const ACTION_MANAGE_RIGHT = 'rights_manager';
    const ACTION_LIST = 'lister';
    const ACTION_VIEW = 'viewer';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;

    const PARAM_APPLICATION_ID = 'application_id';
    const PARAM_RIGHT_ID = 'right_id';

    public static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }

    /**
     * Helper function for the Application class,
     * pending access to class constants via variables in PHP 5.3
     * e.g.
     * $name = $class :: DEFAULT_ACTION
     *
     * DO NOT USE IN THIS APPLICATION'S CONTEXT
     * Instead use:
     * - self :: DEFAULT_ACTION in the context of this class
     * - YourApplicationManager :: DEFAULT_ACTION in all other application
     * classes
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
