<?php
namespace application\ehb_helpdesk;

use common\libraries\WebApplication;

class Manager extends WebApplication
{
    const APPLICATION_NAME = 'rt';
    const ACTION_CREATE = 'creator';
    const DEFAULT_ACTION = self :: ACTION_CREATE;

    /**
     * Constructor
     *
     * @param $user_id int
     */
    public function __construct($user)
    {
        parent :: __construct($user);
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
    public function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }
}
