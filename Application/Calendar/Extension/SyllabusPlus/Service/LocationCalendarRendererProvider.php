<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\LocationEventParser;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class LocationCalendarRendererProvider extends CalendarRendererProvider
{

    /**
     *
     * @var string
     */
    private $year;

    /**
     *
     * @var string
     */
    private $locationIdentifier;

    /**
     *
     * @param CalendarService $calendarService
     * @param string $year
     * @param string $locationIdentifier
     * @param \Chamilo\Core\User\Storage\DataClass\User $dataUser
     * @param \Chamilo\Core\User\Storage\DataClass\User $viewingUser
     * @param string[] $displayParameters;
     */
    public function __construct(CalendarService $calendarService, $year, $locationIdentifier, User $dataUser, 
        User $viewingUser, $displayParameters)
    {
        parent::__construct($calendarService, $dataUser, $viewingUser, $displayParameters);
        
        $this->year = $year;
        $this->locationIdentifier = $locationIdentifier;
    }

    /**
     *
     * @return string
     */
    public function getLocationIdentifier()
    {
        return $this->locationIdentifier;
    }

    /**
     *
     * @param string $locationIdentifier
     */
    public function setLocationIdentifier($locationIdentifier)
    {
        $this->locationIdentifier = $locationIdentifier;
    }

    /**
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     *
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider::getCalendarEvents()
     */
    public function getCalendarEvents($startTime, $endTime)
    {
        return $this->getCalendarService()->getEventsByYearAndLocationAndBetweenDates(
            $this->getYear(), 
            $this->getLocationIdentifier(), 
            $startTime, 
            $endTime);
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider::getEventParser()
     */
    public function getEventParser(\Chamilo\Core\User\Storage\DataClass\User $dataUser, $calendarEvent, $startTime, 
        $endTime)
    {
        return new LocationEventParser($this->getDataUser(), $calendarEvent, $startTime, $endTime);
    }
}