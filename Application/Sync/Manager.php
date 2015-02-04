<?php
namespace Ehb\Application\Sync;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const APPLICATION_NAME = 'sync';
    const ACTION_BROWSE = 'browser';
    const ACTION_BAMAFLEX = 'bamaflex';
    const ACTION_ATLANTIS = 'atlantis';
    const ACTION_CAS = 'cas';
    const ACTION_DATA = 'data';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;

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
