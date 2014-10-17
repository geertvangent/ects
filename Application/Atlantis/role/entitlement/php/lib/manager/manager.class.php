<?php
namespace application\atlantis\role\entitlement;

use libraries\architecture\Application;

class Manager extends Application
{
    const PARAM_ACTION = 'entitlement_action';
    const ACTION_BROWSE = 'browser';
    const ACTION_VIEW = 'viewer';
    const ACTION_DELETE = 'deleter';
    const ACTION_EDIT = 'editor';
    const ACTION_RIGHTS = 'rights';
    const ACTION_LIST = 'lister';
    const PARAM_ENTITLEMENT_ID = 'entitlement_id';
    const DEFAULT_ACTION = self :: ACTION_LIST;

    public static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }

    /**
     * Helper function for the Application class, pending access to class constants via variables in PHP 5.3 e.g. $name
     * = $class :: APPLICATION_NAME DO NOT USE IN THIS APPLICATION'S CONTEXT Instead use: - self :: APPLICATION_NAME in
     * the context of this class - YourApplicationManager :: APPLICATION_NAME in all other application classes
     */
    public function get_application_name()
    {
        return self :: APPLICATION_NAME;
    }

    public function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }
}
