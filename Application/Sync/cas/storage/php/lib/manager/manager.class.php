<?php
namespace application\ehb_sync\cas\storage;

use common\libraries\SubManager;

class Manager extends SubManager
{
    const PARAM_ACTION = 'account_action';
    const ACTION_BROWSE = 'browser';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;

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
