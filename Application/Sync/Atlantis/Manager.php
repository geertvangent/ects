<?php
namespace Ehb\Application\Sync\Atlantis;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const ACTION_BROWSE = 'Browser';
    const ACTION_DISCOVERY = 'Discovery';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_ACTION = 'discovery_action';

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $user
     * @param string $application
     */
    public function __construct(\Symfony\Component\HttpFoundation\Request $request, $user = null, $application = null)
    {
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "18000");
        parent :: __construct($request, $user, $application);
    }
}
