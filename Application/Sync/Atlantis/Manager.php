<?php
namespace Ehb\Application\Sync\Atlantis;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const ACTION_BROWSE = 'browser';
    const ACTION_DISCOVERY = 'discovery';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_ACTION = 'discovery_action';

    public function __construct($user = null, $application = null)
    {
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "18000");
        parent :: __construct($user, $application);
    }
}
