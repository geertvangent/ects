<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\UserEventParser;

/**
 *
 * @package Chamilo\Application\Calendar\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class UserCalendarRendererProvider extends CalendarRendererProvider
{

    /**
     *
     * @var CalendarService
     */
    private $calendarService;

    /**
     *
     * @param CalendarService $calendarService
     * @param \Chamilo\Core\User\Storage\DataClass\User $dataUser
     * @param \Chamilo\Core\User\Storage\DataClass\User $viewingUser
     * @param string[] $displayParameters;
     */
    public function __construct(CalendarService $calendarService, User $dataUser, User $viewingUser, $displayParameters)
    {
        parent::__construct($dataUser, $viewingUser, $displayParameters);

        $this->calendarService = $calendarService;
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
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider::getCalendarEvents()
     */
    public function getCalendarEvents($startTime, $endTime)
    {
        return $this->getCalendarService()->getEventsForUserAndBetweenDates($this->getDataUser(), $startTime, $endTime);
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider::getEventParser()
     */
    public function getEventParser(\Chamilo\Core\User\Storage\DataClass\User $dataUser, $calendarEvent, $startTime,
        $endTime)
    {
        return new UserEventParser($this->getDataUser(), $calendarEvent, $startTime, $endTime);
    }
}