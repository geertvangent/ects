<?php
namespace Ehb\Application\Sync\Bamaflex;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Application\ApplicationConfigurationInterface;

abstract class Manager extends Application
{
    const ACTION_BROWSE = 'Browser';
    const ACTION_ALL_USERS = 'AllUsers';
    const ACTION_COURSE_CATEGORIES = 'CourseCategories';
    const ACTION_GROUPS = 'Groups';
    const ACTION_ARCHIVE_GROUPS = 'ArchiveGroups';
    const ACTION_COURSES = 'Courses';
    const ACTION_ADMINS = 'Admins';
    const ACTION_STATE = 'State';
    const DEFAULT_ACTION = self::ACTION_BROWSE;
    const PARAM_ACTION = 'bamaflex_action';

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $user
     * @param string $application
     */
    public function __construct(ApplicationConfigurationInterface $applicationConfiguration)
    {
        ini_set("memory_limit", "-1");
        ini_set("max_execution_time", "18000");
        parent::__construct($applicationConfiguration);
    }
}
