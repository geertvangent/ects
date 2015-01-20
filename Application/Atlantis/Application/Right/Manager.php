<?php
namespace Ehb\Application\Atlantis\Application\Right;

use Chamilo\Libraries\Architecture\Application\Application;

/**
 * $Id: elude_manager.class.php
 * 
 * @package application.elude
 */
class Manager extends Application
{
    const PARAM_ACTION = 'right_action';
    const ACTION_BROWSE = 'browser';
    const ACTION_VIEW = 'viewer';
    const ACTION_DELETE = 'deleter';
    const ACTION_EDIT = 'editor';
    const ACTION_RIGHTS = 'rights';
    const ACTION_CREATE = 'creator';
    const ACTION_ADD_ROLE = 'add_role';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_RIGHT_ID = 'right_id';
    const PARAM_APPLICATION_ID = 'application_id';

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

    /**
     * Helper function for the Application class, pending access to class constants via variables in PHP 5.3 e.g. $name
     * = $class :: DEFAULT_ACTION DO NOT USE IN THIS APPLICATION'S CONTEXT Instead use: - self :: DEFAULT_ACTION in the
     * context of this class - YourApplicationManager :: DEFAULT_ACTION in all other application classes
     */
    public static function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }
}
