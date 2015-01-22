<?php
namespace Ehb\Application\Sync\Cas\Storage;

use Chamilo\Libraries\Architecture\Application\Application;

class Manager extends Application
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
}
