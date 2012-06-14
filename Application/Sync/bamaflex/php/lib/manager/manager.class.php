<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\SubManager;

class Manager extends SubManager
{
    const ACTION_BROWSE = 'browser';
    const ACTION_USERS = 'users';
    const ACTION_ALL_USERS = 'all_users';
    const ACTION_GROUPS = 'groups';
    
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    
    const PARAM_ACTION = 'bamaflex_action';

    static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }

    function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }

    static function launch($application)
    {
        parent :: launch(null, $application);
    }
}
?>