<?php
namespace application\ehb_sync\atlantis;

use libraries\SubManager;

class Manager extends SubManager
{
    const ACTION_BROWSE = 'browser';
    const ACTION_DISCOVERY = 'discovery';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_ACTION = 'discovey_action';

    public function __construct($parent)
    {
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "18000");
        parent :: __construct($parent);
    }

    public static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }

    public function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }

    public static function launch($application)
    {
        parent :: launch(null, $application);
    }
}
