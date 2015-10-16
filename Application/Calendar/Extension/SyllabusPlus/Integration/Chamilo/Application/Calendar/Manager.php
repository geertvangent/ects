<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Application\Calendar;

use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\EventParser;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;
use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Application\Calendar\Service\AvailabilityService;
use Chamilo\Application\Calendar\Repository\AvailabilityRepository;
use Chamilo\Application\Calendar\Storage\DataClass\AvailableCalendar;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Application\Calendar\Storage\DataClass\Availability;
use Chamilo\Configuration\Configuration;
use Chamilo\Application\Calendar\Architecture\InternalCalendar;

/**
 *
 * @package Chamilo\Application\Calendar\Extension\Personal\Integration\Chamilo\Application\Calendar
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Manager extends InternalCalendar
{

    /**
     *
     * @see \Chamilo\Application\Calendar\CalendarInterface::getEvents()
     */
    public function getEvents(
        \Chamilo\Libraries\Calendar\Renderer\Service\CalendarRendererProvider $calendarRendererProvider,
        $requestedSourceType, $fromDate, $toDate)
    {
        $calendarService = new CalendarService(CalendarRepository :: getInstance());
        $events = array();

        if ($calendarService->isConfigured(Configuration :: get_instance()))
        {
            $availabilityService = new AvailabilityService(new AvailabilityRepository());
            $packageContext = ClassnameUtilities :: getInstance()->getNamespaceParent(__NAMESPACE__, 4);
            $packageName = ClassnameUtilities :: getInstance()->getPackageNameFromNamespace($packageContext);

            $activeAvailability = $availabilityService->getAvailabilityByUserAndCalendarTypeAndCalendarIdentifier(
                $calendarRendererProvider->getDataUser(),
                $packageContext,
                $packageName);

            if ($activeAvailability instanceof Availability && $activeAvailability->getAvailability() == 1)
            {
                $eventResultSet = $calendarService->getEventsForUserAndBetweenDates(
                    $calendarRendererProvider->getDataUser(),
                    $fromDate,
                    $toDate);

                while ($calenderEvent = $eventResultSet->next_result())
                {
                    $eventParser = new EventParser($calenderEvent, $fromDate, $toDate);
                    $events = array_merge($events, $eventParser->getEvents());
                }
            }
        }

        return $events;
    }

    /**
     *
     * @return \Chamilo\Application\Calendar\Storage\DataClass\AvailableCalendar[]
     */
    public function getCalendars()
    {
        $calendarService = new CalendarService(CalendarRepository :: getInstance());
        $calendars = array();

        if ($calendarService->isConfigured(Configuration :: get_instance()))
        {
            $package = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: package();

            $calendar = new AvailableCalendar();
            $calendar->setIdentifier(ClassnameUtilities :: getInstance()->getPackageNameFromNamespace($package));
            $calendar->setType($package);
            $calendar->setName(Translation :: get('TypeName', null, $package));

            $calendars[] = $calendar;
        }

        return $calendars;
    }
}