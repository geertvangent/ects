<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;

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

    private $userCalendar;

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

    function getUserCalendar()
    {
        if (! isset($this->userCalendar))
        {
            $userId = $this->getRequest()->query->get(self :: PARAM_USER_USER_ID);
            if (! $userId)
            {
                $userId = $this->get_user_id();
            }
            elseif ($userId != $this->get_user_id() && ! $this->get_user()->get_platformadmin() &&
                 $this->get_user()->get_status() != User :: STATUS_TEACHER)
            {
                throw new NotAllowedException();
            }
            $this->setUserCalendar(
                \Chamilo\Core\User\Storage\DataManager :: retrieve_by_id(User :: class_name(), $userId));
        }
        return $this->userCalendar;
    }

    function setUserCalendar($userCalendar)
    {
        $this->userCalendar = $userCalendar;
    }
}
