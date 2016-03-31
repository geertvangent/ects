<?php
namespace Ehb\Application\Avilarts\Request;

use Chamilo\Libraries\Architecture\Application\Application;
use Ehb\Application\Avilarts\Rights\CourseManagementRights;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'request_action';
    const PARAM_REQUEST_ID = 'request_id';
    const PARAM_RESET_CACHE = 'reset_cache';
    const ACTION_BROWSE = 'Browser';
    const ACTION_CREATE = 'Creator';
    const ACTION_DELETE = 'Deleter';
    const ACTION_DENY = 'Denier';
    const ACTION_GRANT = 'Granter';
    const ACTION_RIGHTS = 'Rights';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;

    public function request_allowed()
    {
        if ($this->get_user()->is_platform_admin())
        {
            return true;
        }

        $course_types = \Ehb\Application\Avilarts\CourseType\Storage\DataManager :: retrieve_active_course_types();
        while ($course_type = $course_types->next_result())
        {
            if (CourseManagementRights :: get_instance()->is_allowed(
                CourseManagementRights :: REQUEST_COURSE_RIGHT,
                $course_type->get_id(),
                CourseManagementRights :: TYPE_COURSE_TYPE))
            {
                return true;
            }
        }

        return false;
    }
}
?>