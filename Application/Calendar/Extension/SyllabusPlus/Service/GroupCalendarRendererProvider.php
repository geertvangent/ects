<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\GroupEventParser;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class GroupCalendarRendererProvider extends CalendarRendererProvider
{

    /**
     *
     * @var CalendarService
     */
    private $calendarService;

    /**
     *
     * @var string
     */
    private $year;

    /**
     *
     * @var string
     */
    private $groupIdentifier;

    /**
     *
     * @param CalendarService $calendarService
     * @param string $year
     * @param string $groupIdentifier
     * @param \Chamilo\Core\User\Storage\DataClass\User $dataUser
     * @param \Chamilo\Core\User\Storage\DataClass\User $viewingUser
     * @param string[] $displayParameters;
     */
    public function __construct(CalendarService $calendarService, $year, $groupIdentifier, User $dataUser,
        User $viewingUser, $displayParameters)
    {
        parent::__construct($dataUser, $viewingUser, $displayParameters);

        $this->calendarService = $calendarService;
        $this->year = $year;
        $this->groupIdentifier = $groupIdentifier;
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService
     */
    protected function getCalendarService()
    {
        return $this->calendarService;
    }

    /**
     *
     * @param \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService $calendarService
     */
    protected function setCalendarService(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     *
     * @return string
     */
    public function getGroupIdentifier()
    {
        return $this->groupIdentifier;
    }

    /**
     *
     * @param string $groupIdentifier
     */
    public function setGroupIdentifier($groupIdentifier)
    {
        $this->groupIdentifier = $groupIdentifier;
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
        return $this->getCalendarService()->getEventsForGroupAndBetweenDates(
            $this->getYear(),
            $this->getGroupIdentifier(),
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
        return new GroupEventParser($this->getDataUser(), $calendarEvent, $startTime, $endTime);
    }
}