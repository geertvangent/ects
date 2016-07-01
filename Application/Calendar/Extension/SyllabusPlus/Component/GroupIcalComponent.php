<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\File\Redirect;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\GroupCalendarRendererProvider;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class GroupIcalComponent extends IcalComponent
{

    /**
     *
     * @var string
     */
    private $groupIdentifier;

    /**
     *
     * @var string
     */
    private $year;

    /**
     *
     * @return string
     */
    protected function getIcalDownloadUrl()
    {
        $icalDownloadUrl = new Redirect(
            array(
                Application::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => Manager::ACTION_ICAL_GROUP,
                self::PARAM_YEAR => $this->getYear(),
                self::PARAM_GROUP_ID => $this->getGroupIdentifier(),
                self::PARAM_DOWNLOAD => 1));

        return $icalDownloadUrl->getUrl();
    }

    /**
     *
     * @return string
     */
    protected function getIcalExternalUrl()
    {
        $icalExternalUrl = new Redirect(
            array(
                Application::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => Manager::ACTION_ICAL_GROUP,
                self::PARAM_YEAR => $this->getYear(),
                self::PARAM_GROUP_ID => $this->getGroupIdentifier(),
                User::PROPERTY_SECURITY_TOKEN => $this->getUserCalendar()->get_security_token()));

        return $icalExternalUrl->getUrl();
    }

    /**
     *
     * @return \Chamilo\Application\Calendar\Service\CalendarRendererProvider
     */
    protected function getCalendarRendererProvider()
    {
        if (! isset($this->calendarRendererProvider))
        {
            $this->calendarRendererProvider = new GroupCalendarRendererProvider(
                $this->getYear(),
                $this->getGroupIdentifier(),
                $this->getUserCalendar(),
                $this->get_user(),
                array());
        }

        return $this->calendarRendererProvider;
    }

    /**
     *
     * @return string
     */
    protected function getGroupIdentifier()
    {
        if (! isset($this->groupIdentifier))
        {
            $this->groupIdentifier = $this->getRequest()->query->get(self::PARAM_GROUP_ID);
        }

        return $this->groupIdentifier;
    }

    /**
     *
     * @return string
     */
    protected function getYear()
    {
        if (! isset($this->year))
        {
            $this->year = $this->getRequest()->query->get(self::PARAM_YEAR);
        }

        return $this->year;
    }
}