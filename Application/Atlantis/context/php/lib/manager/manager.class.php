<?php
namespace application\atlantis\context;

use common\libraries\SubManager;

class Manager extends SubManager
{
    const PARAM_ACTION = 'context_action';
    
    const ACTION_BROWSE = 'browser';
    const ACTION_DELETE = 'delete';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    
    const PARAM_CONTEXT_ID = 'context_id';

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