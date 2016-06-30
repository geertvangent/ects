<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

use Chamilo\Configuration\Configuration;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\UserEventParser;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;

/**
 *
 * @package Chamilo\Application\Calendar\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class UserCalendarRendererProvider extends \Chamilo\Libraries\Calendar\Renderer\Service\CalendarRendererProvider
{

    /**
     *
     * @param int $sourceType
     * @param integer $startTime
     * @param integer $endTime
     */
    public function aggregateEvents($requestedSourceType, $startTime, $endTime)
    {
        $events = array();

        if ($requestedSourceType != self::SOURCE_TYPE_EXTERNAL)
        {
            // TODO: This is basically almost the same as the logic in the integration with the Calendar application,
            // the logic should therefore be split off into it's own service
            $calendarService = new CalendarService(CalendarRepository::getInstance());
            $events = array();

            if ($calendarService->isConfigured(Configuration::get_instance()))
            {
                $eventResultSet = $calendarService->getEventsForUserAndBetweenDates(
                    $this->getDataUser(),
                    $startTime,
                    $endTime);

                while ($calenderEvent = $eventResultSet->next_result())
                {
                    $eventParser = new UserEventParser($this->getDataUser(), $calenderEvent, $startTime, $endTime);
                    $events = array_merge($events, $eventParser->getEvents());
                }
            }
        }

        return $events;
    }
}