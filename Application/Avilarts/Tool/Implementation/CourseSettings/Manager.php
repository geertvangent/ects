<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseSettings;

/**
 * $Id: course_settings_tool.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.course_settings
 */

/**
 * This tool allows a user to publish course_settingss in his or her course.
 */
abstract class Manager extends \Ehb\Application\Avilarts\Tool\Manager
{
    const DEFAULT_ACTION = self :: ACTION_UPDATE;
}
