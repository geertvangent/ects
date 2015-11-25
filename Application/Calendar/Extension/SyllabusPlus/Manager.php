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
    const PARAM_USER_USER_ID = 'user_id';
    const PARAM_ACTION = 'syllabus_action';
    const PARAM_ACTIVITY_ID = 'activity_id';
    const PARAM_ACTIVITY_TIME = 'activity_time';
    const PARAM_FIRSTLETTER = 'firstletter';
    // Actions
    const ACTION_VIEW = 'Viewer';
    const ACTION_BROWSER = 'Browser';
    const ACTION_USER_BROWSER = 'UserBrowser';

    // Default action
    const DEFAULT_ACTION = self :: ACTION_VIEW;

    /**
     * gets the user editing url
     *
     * @param return the requested url
     */
    public function get_browser_url($user)
    {
        return $this->get_url(
            array(self :: PARAM_ACTION => self :: ACTION_BROWSER, self :: PARAM_USER_USER_ID => $user->get_id()));
    }
}
