<?php
namespace Ehb\Application\Sync\Bamaflex;

use Chamilo\Libraries\Architecture\Application\Application;

class Manager extends Application
{
    const ACTION_BROWSE = 'browser';
    const ACTION_ALL_USERS = 'all_users';
    const ACTION_COURSE_CATEGORIES = 'course_categories';
    const ACTION_GROUPS = 'groups';
    const ACTION_ARCHIVE_GROUPS = 'archive_groups';
    const ACTION_COURSES = 'courses';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_ACTION = 'bamaflex_action';

    public function __construct($user = null, $application = null)
    {
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "18000");
        parent :: __construct($user, $application);
    }

    public static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }

    public function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }
}
