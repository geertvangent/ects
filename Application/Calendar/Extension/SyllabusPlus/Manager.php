<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\Calendar\Table\Type\MiniMonthCalendar;
use Chamilo\Libraries\Platform\Configuration\LocalSetting;
use Chamilo\Libraries\Platform\Session\Request;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
abstract class Manager extends Application
{
    // Parameters
    const PARAM_USER_USER_ID = 'user_id';
    const PARAM_ACTION = 'syllabus_action';
    const PARAM_ACTIVITY_ID = 'activity_id';
    const PARAM_YEAR = 'year';
    const PARAM_ACTIVITY_TIME = 'activity_time';
    const PARAM_FIRSTLETTER = 'firstletter';
    const PARAM_DOWNLOAD = 'download';
    const PARAM_GROUP_ID = 'group_id';
    const PARAM_LOCATION_ID = 'location_id';
    const PARAM_PRINT = 'print';

    // Actions
    const ACTION_USER = 'User';
    const ACTION_CODE = 'Code';
    const ACTION_VIEW_USER_EVENT = 'UserEventViewer';
    const ACTION_VIEW_GROUP_EVENT = 'GroupEventViewer';
    const ACTION_VIEW_LOCATION_EVENT = 'LocationEventViewer';
    const ACTION_ICAL_USER = 'Ical';
    const ACTION_ICAL_GROUP = 'GroupIcal';
    const ACTION_ICAL_LOCATION = 'LocationIcal';
    const ACTION_BROWSE_USER = 'UserBrowser';
    const ACTION_BROWSE_GROUP = 'GroupBrowser';
    const ACTION_BROWSE_LOCATION = 'LocationBrowser';

    // Default action
    const DEFAULT_ACTION = self::ACTION_BROWSE_USER;

    private $userCalendar;

    /**
     *
     * @var integer
     */
    private $currentTime;

    /**
     *
     * @var \Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer
     */
    private $tabs;

    /**
     *
     * @param User $user
     * @return string
     */
    public function get_browser_url(User $user)
    {
        return $this->get_url(
            array(self::PARAM_ACTION => self::ACTION_BROWSE_USER, self::PARAM_USER_USER_ID => $user->get_id()));
    }

    /**
     *
     * @throws NotAllowedException
     */
    public function checkAuthorization()
    {
        if (! $this->hasAuthorization())
        {
            throw new NotAllowedException();
        }
    }

    /**
     *
     * @return boolean
     */
    public function hasAuthorization()
    {
        $userId = $this->getUserIdForCalendar();

        if ($userId != $this->get_user_id() && ! $this->getUser()->get_platformadmin() &&
             $this->getUser()->get_status() != User::STATUS_TEACHER)
        {
            return false;
        }

        return true;
    }

    /**
     *
     * @return integer
     */
    public function getUserIdForCalendar()
    {
        $userId = $this->getRequest()->query->get(self::PARAM_USER_USER_ID);

        if (! $userId)
        {
            $userId = $this->get_user_id();
        }

        return $userId;
    }

    /**
     *
     * @return \Chamilo\Core\User\Storage\DataClass\User
     */
    public function getUserCalendar()
    {
        if (! isset($this->userCalendar))
        {
            $this->setUserCalendar(
                \Chamilo\Core\User\Storage\DataManager::retrieve_by_id(
                    User::class_name(),
                    $this->getUserIdForCalendar()));
        }

        return $this->userCalendar;
    }

    /**
     *
     * @param \Chamilo\Core\User\Storage\DataClass\User $userCalendar
     */
    public function setUserCalendar(User $userCalendar)
    {
        $this->userCalendar = $userCalendar;
    }

    /**
     *
     * @return string
     */
    public function getCurrentRendererType()
    {
        $requestRendererType = $this->getRequest()->query->get(ViewRenderer::PARAM_TYPE);

        if (! $requestRendererType)
        {
            return LocalSetting::getInstance()->get('default_view', 'Chamilo\Libraries\Calendar');
        }

        return $requestRendererType;
    }

    /**
     *
     * @return int
     */
    public function getCurrentRendererTime()
    {
        if (! isset($this->currentTime))
        {
            $this->currentTime = Request::get(ViewRenderer::PARAM_TIME, time());
        }

        return $this->currentTime;
    }

    public function setCurrentRendererTime($currentTime)
    {
        $this->currentTime = $currentTime;
    }

    public function getMiniMonthMarkPeriod()
    {
        switch ($this->getCurrentRendererType())
        {
            case ViewRenderer::TYPE_DAY :
                return MiniMonthCalendar::PERIOD_DAY;
            case ViewRenderer::TYPE_MONTH :
                return MiniMonthCalendar::PERIOD_MONTH;
            case ViewRenderer::TYPE_WEEK :
                return MiniMonthCalendar::PERIOD_WEEK;
            default :
                return MiniMonthCalendar::PERIOD_DAY;
        }
    }

    /**
     *
     * @return boolean
     */
    protected function isPrintRequested()
    {
        return $this->getRequest()->query->get(self::PARAM_PRINT) == 1;
    }
}
