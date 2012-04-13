<?php
namespace application\atlantis\application;

use common\libraries\SubManager;

use common\libraries\Utilities;
use common\libraries\WebApplication;
use common\libraries\Request;

class Manager extends SubManager
{
    const PARAM_ACTION = 'application_action';
    const ACTION_BROWSE = 'browser';
    const ACTION_VIEW = 'viewer';
    const ACTION_DELETE = 'deleter';
    const ACTION_EDIT = 'editor';
    const ACTION_RIGHTS = 'rights';
    const ACTION_CREATE = 'creator';
    const ACTION_MANAGE_RIGHT = 'rights_manager';
    const ACTION_LIST = 'lister';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    
    const PARAM_APPLICATION_ID = 'application_id';
    const PARAM_RIGHT_ID = 'right_id';

    static function get_action_parameter()
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
    function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }

    static function launch($application)
    {
        parent :: launch(null, $application);
    }

}
?>