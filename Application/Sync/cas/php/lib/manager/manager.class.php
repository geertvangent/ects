<?php
namespace application\ehb_sync\cas;

use common\libraries\SubManager;

class Manager extends SubManager
{
    const ACTION_BROWSE = 'browser';
    const ACTION_ALL_USERS = 'all_users';
    const ACTION_STATISTICS = 'statistics';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_ACTION = 'cas_action';

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
