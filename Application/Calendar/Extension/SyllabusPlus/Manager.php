<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Calendar\Table\Type\MiniMonthCalendar;
use Chamilo\Libraries\Platform\Configuration\LocalSetting;
use Chamilo\Core\User\Component\UserSettingsComponent;

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
    const PARAM_DOWNLOAD = 'download';

    // Actions
    const ACTION_VIEW = 'Viewer';
    const ACTION_BROWSER = 'Browser';
    const ACTION_USER_BROWSER = 'UserBrowser';
    const ACTION_CODE = 'Code';
    const ACTION_ICAL = 'Ical';
    const ACTION_PRINT = 'Printer';
    const ACTION_GROUP = 'Group';

    // Default action
    const DEFAULT_ACTION = self :: ACTION_VIEW;

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
            array(self :: PARAM_ACTION => self :: ACTION_BROWSER, self :: PARAM_USER_USER_ID => $user->get_id()));
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
             $this->getUser()->get_status() != User :: STATUS_TEACHER)
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
        $userId = $this->getRequest()->query->get(self :: PARAM_USER_USER_ID);

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
                \Chamilo\Core\User\Storage\DataManager :: retrieve_by_id(
                    User :: class_name(),
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
     * @return \Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer
     */
    public function getTabs()
    {
        if (! isset($this->tabs))
        {
            $this->tabs = new DynamicVisualTabsRenderer('calendar');
            $this->addTypeTabs($this->tabs);
            $this->addGeneralTabs($this->tabs);
        }

        return $this->tabs;
    }

    /**
     *
     * @param \Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer $tabs
     */
    private function addGeneralTabs(DynamicVisualTabsRenderer $tabs)
    {
        $currentAction = $this->getRequest()->query->get(self :: PARAM_ACTION);

        $settingsUrl = new Redirect(
            array(
                Application :: PARAM_CONTEXT => \Chamilo\Core\User\Manager :: context(),
                Application :: PARAM_ACTION => \Chamilo\Core\User\Manager :: ACTION_USER_SETTINGS,
                UserSettingsComponent :: PARAM_CONTEXT => 'Chamilo\Libraries\Calendar'));

        $tabs->add_tab(
            new DynamicVisualTab(
                'configuration',
                Translation :: get('ConfigComponent'),
                Theme :: getInstance()->getImagePath(self :: package(), 'Tab/Configuration'),
                $settingsUrl->getUrl(),
                false,
                false,
                DynamicVisualTab :: POSITION_RIGHT,
                DynamicVisualTab :: DISPLAY_BOTH_SELECTED));

        $userBrowserUrl = new Redirect(
            array(self :: PARAM_CONTEXT => self :: package(), self :: PARAM_ACTION => self :: ACTION_USER_BROWSER));

        $tabs->add_tab(
            new DynamicVisualTab(
                self :: ACTION_USER_BROWSER,
                Translation :: get(self :: ACTION_USER_BROWSER . 'Component'),
                Theme :: getInstance()->getImagePath(self :: package(), 'Tab/' . self :: ACTION_USER_BROWSER),
                $userBrowserUrl->getUrl(),
                $currentAction == self :: ACTION_USER_BROWSER,
                false,
                DynamicVisualTab :: POSITION_RIGHT,
                DynamicVisualTab :: DISPLAY_BOTH_SELECTED));

        $icalDownloadUrl = new Redirect(
            array(
                self :: PARAM_CONTEXT => self :: package(),
                self :: PARAM_ACTION => Manager :: ACTION_ICAL,
                self :: PARAM_USER_USER_ID => $this->getUserCalendar()->getId(),
                self :: PARAM_DOWNLOAD => 1));

        $downloadParameter = $this->getRequest()->query->get(self :: PARAM_DOWNLOAD);

        $tabs->add_tab(
            new DynamicVisualTab(
                'ICalDownload',
                Translation :: get('ICalDownload'),
                Theme :: getInstance()->getImagePath(self :: package(), 'Tab/ICalDownload'),
                $icalDownloadUrl->getUrl(),
                $currentAction == self :: ACTION_ICAL && $downloadParameter == 1,
                false,
                DynamicVisualTab :: POSITION_RIGHT,
                DynamicVisualTab :: DISPLAY_BOTH_SELECTED));

        $iCalUrl = new Redirect(
            array(
                self :: PARAM_CONTEXT => self :: package(),
                self :: PARAM_ACTION => Manager :: ACTION_ICAL,
                self :: PARAM_USER_USER_ID => $this->getUserCalendar()->getId()));

        $tabs->add_tab(
            new DynamicVisualTab(
                'ICalExternal',
                Translation :: get('ICalExternal'),
                Theme :: getInstance()->getImagePath(self :: package(), 'Tab/ICalExternal'),
                $iCalUrl->getUrl(),
                $currentAction == self :: ACTION_ICAL,
                false,
                DynamicVisualTab :: POSITION_RIGHT,
                DynamicVisualTab :: DISPLAY_BOTH_SELECTED));

        $groupUrl = new Redirect(
            array(
                self :: PARAM_CONTEXT => self :: package(),
                self :: PARAM_ACTION => self :: ACTION_GROUP));

        $tabs->add_tab(
            new DynamicVisualTab(
                self :: ACTION_GROUP,
                Translation :: get(self :: ACTION_GROUP . 'Component'),
                Theme :: getInstance()->getImagePath(self :: package(), 'Tab/' . self :: ACTION_GROUP),
                $groupUrl->getUrl(),
                $currentAction == self :: ACTION_GROUP,
                false,
                DynamicVisualTab :: POSITION_RIGHT,
                DynamicVisualTab :: DISPLAY_BOTH_SELECTED));


        $printUrl = new Redirect(
            array(
                self :: PARAM_CONTEXT => self :: package(),
                self :: PARAM_ACTION => self :: ACTION_PRINT,
                ViewRenderer :: PARAM_TYPE => $this->getCurrentRendererType(),
                ViewRenderer :: PARAM_TIME => $this->getCurrentRendererTime(),
                self :: PARAM_USER_USER_ID => $this->getUserCalendar()->get_id()));

        $tabs->add_tab(
            new DynamicVisualTab(
                self :: ACTION_PRINT,
                Translation :: get(self :: ACTION_PRINT . 'Component'),
                Theme :: getInstance()->getImagePath(self :: package(), 'Tab/' . self :: ACTION_PRINT),
                $printUrl->getUrl(),
                $currentAction == self :: ACTION_PRINT,
                false,
                DynamicVisualTab :: POSITION_RIGHT,
                DynamicVisualTab :: DISPLAY_BOTH_SELECTED,
                DynamicVisualTab :: TARGET_POPUP));
    }

    /**
     *
     * @param \Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer $tabs
     */
    private function addTypeTabs(DynamicVisualTabsRenderer $tabs)
    {
        $typeUrl = $this->get_url(
            array(
                self :: PARAM_ACTION => self :: ACTION_BROWSER,
                self :: PARAM_USER_USER_ID => $this->getUserCalendar()->getId(),
                ViewRenderer :: PARAM_TYPE => ViewRenderer :: MARKER_TYPE));

        $todayUrl = $this->get_url(
            array(
                self :: PARAM_ACTION => self :: ACTION_BROWSER,
                self :: PARAM_USER_USER_ID => $this->getUserCalendar()->getId(),
                ViewRenderer :: PARAM_TYPE => $this->getCurrentRendererType(),
                ViewRenderer :: PARAM_TIME => time()));

        $rendererTypes = array(
            ViewRenderer :: TYPE_MONTH,
            ViewRenderer :: TYPE_WEEK,
            ViewRenderer :: TYPE_DAY,
            ViewRenderer :: TYPE_YEAR,
            ViewRenderer :: TYPE_LIST);

        $rendererTypeTabs = ViewRenderer :: getTabs($rendererTypes, $typeUrl, $todayUrl);

        foreach ($rendererTypeTabs as $rendererTypeTab)
        {
            $rendererTypeTab->set_selected($this->getCurrentRendererType() == $rendererTypeTab->get_id());

            $tabs->add_tab($rendererTypeTab);
        }
    }

    /**
     *
     * @return string
     */
    public function getCurrentRendererType()
    {
        $requestRendererType = $this->getRequest()->query->get(ViewRenderer :: PARAM_TYPE);

        if (! $requestRendererType)
        {
            return LocalSetting :: getInstance()->get('default_view', 'Chamilo\Libraries\Calendar');
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
            $this->currentTime = Request :: get(ViewRenderer :: PARAM_TIME, time());
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
            case ViewRenderer :: TYPE_DAY :
                return MiniMonthCalendar :: PERIOD_DAY;
            case ViewRenderer :: TYPE_MONTH :
                return MiniMonthCalendar :: PERIOD_MONTH;
            case ViewRenderer :: TYPE_WEEK :
                return MiniMonthCalendar :: PERIOD_WEEK;
            default :
                return MiniMonthCalendar :: PERIOD_DAY;
        }
    }
}
