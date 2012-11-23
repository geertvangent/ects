<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\SubManager;

class Manager extends SubManager
{
    const ACTION_BROWSE = 'browser';
    const ACTION_ALL_USERS = 'all_users';
    const ACTION_NEW_USERS = 'new_users';
    const ACTION_UPDATE_USERS = 'update_users';
    const ACTION_COURSE_CATEGORIES = 'course_categories';
    const ACTION_GROUPS = 'groups';
    const ACTION_ARCHIVE_GROUPS = 'archive_groups';
    const ACTION_COURSES = 'courses';

    const DEFAULT_ACTION = self :: ACTION_BROWSE;

    const PARAM_ACTION = 'bamaflex_action';

    function __construct($parent)
    {
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "18000");
        parent :: __construct($parent);
    }

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