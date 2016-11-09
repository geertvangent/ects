<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

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