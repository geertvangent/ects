<?php
namespace application\discovery;

use common\libraries\WebApplication;
/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DiscoveryManager extends WebApplication
{
    const APPLICATION_NAME = 'discovery';

    const ACTION_BROWSE = 'browser';
    const ACTION_VIEW = 'viewer';
    const ACTION_MODULE = 'module';
    const ACTION_RIGHTS = 'rights';

    const PARAM_USER_ID = 'user_id';
    const PARAM_MODULE_ID = 'module_id';
    const PARAM_CONTENT_TYPE = 'content_type';

    const DEFAULT_ACTION = self :: ACTION_VIEW;

    /**
     * Constructor
     * @param User $user The current user
     */
    function __construct($user = null)
    {
        parent :: __construct($user);
    }

    function get_application_name()
    {
        return self :: APPLICATION_NAME;
    }

    /**
     * Helper function for the Application class,
     * pending access to class constants via variables in PHP 5.3
     * e.g. $name = $class :: DEFAULT_ACTION
     *
     * DO NOT USE IN THIS APPLICATION'S CONTEXT
     * Instead use:
     * - self :: DEFAULT_ACTION in the context of this class
     * - YourApplicationManager :: DEFAULT_ACTION in all other application classes
     */
    function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }
}
?>