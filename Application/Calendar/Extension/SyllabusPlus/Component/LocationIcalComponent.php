<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\File\Redirect;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\LocationCalendarRendererProvider;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class LocationIcalComponent extends IcalComponent
{

    /**
     *
     * @var string
     */
    private $locationIdentifier;

    /**
     *
     * @var string
     */
    private $year;

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\IcalComponent::getIcalDownloadUrl()
     */
    protected function getIcalDownloadUrl()
    {
        $icalDownloadUrl = new Redirect(
            array(
                Application::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => Manager::ACTION_ICAL_LOCATION,
                self::PARAM_YEAR => $this->getYear(),
                self::PARAM_LOCATION_ID => $this->getLocationIdentifier(),
                self::PARAM_DOWNLOAD => 1));

        return $icalDownloadUrl->getUrl();
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\IcalComponent::getIcalExternalUrl()
     */
    protected function getIcalExternalUrl()
    {
        $icalExternalUrl = new Redirect(
            array(
                Application::PARAM_CONTEXT => self::package(),
                self::PARAM_ACTION => Manager::ACTION_ICAL_LOCATION,
                self::PARAM_YEAR => $this->getYear(),
                self::PARAM_LOCATION_ID => $this->getLocationIdentifier(),
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
            $this->calendarRendererProvider = new LocationCalendarRendererProvider(
                $this->getYear(),
                $this->getLocationIdentifier(),
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
    protected function getLocationIdentifier()
    {
        if (! isset($this->locationIdentifier))
        {
            $this->locationIdentifier = $this->getRequest()->query->get(self::PARAM_LOCATION_ID);
        }

        return $this->locationIdentifier;
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