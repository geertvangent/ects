<?php
namespace Ehb\Application\Atlantis\Role\Entitlement;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'entitlement_action';
    const ACTION_BROWSE = 'Browser';
    const ACTION_VIEW = 'Viewer';
    const ACTION_DELETE = 'Deleter';
    const ACTION_EDIT = 'Editor';
    const ACTION_RIGHTS = 'Rights';
    const ACTION_LIST = 'Lister';
    const PARAM_ENTITLEMENT_ID = 'entitlement_id';
    const DEFAULT_ACTION = self :: ACTION_LIST;

    /**
     * Helper function for the Application class, pending access to class constants via variables in PHP 5.3 e.g.
     * $name
     * = $class :: APPLICATION_NAME DO NOT USE IN THIS APPLICATION'S CONTEXT Instead use: - self :: APPLICATION_NAME in
     * the context of this class - YourApplicationManager :: APPLICATION_NAME in all other application classes
     */
    public function get_application_name()
    {
        return self :: APPLICATION_NAME;
    }
}
