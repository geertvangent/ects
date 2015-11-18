<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus;

use Chamilo\Libraries\Architecture\Application\Application;

/**
 *
 * @package application\calendar
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
abstract class Manager extends Application
{
    // Parameters
    const PARAM_ACTION = 'syllabus_action';
    const PARAM_ACTIVITY_ID = 'activity_id';
    const PARAM_ACTIVITY_TIME = 'activity_time';

    // Actions
    const ACTION_VIEW = 'Viewer';

    // Default action
    const DEFAULT_ACTION = self :: ACTION_VIEW;
}
