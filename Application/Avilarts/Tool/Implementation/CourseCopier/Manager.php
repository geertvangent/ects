<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseCopier;

/**
 * This tool implements the course emptier tool for a course.
 *
 * @author Mattias De Pauw - Hogeschool Gent
 */
abstract class Manager extends \Ehb\Application\Avilarts\Tool\Manager
{
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const ACTION_BROWSE = 'Browser';
}
