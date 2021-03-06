<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Application\Calendar\Service;

use Chamilo\Application\Calendar\Architecture\InternalCalendar;
use Chamilo\Application\Calendar\Repository\AvailabilityRepository;
use Chamilo\Application\Calendar\Service\AvailabilityService;
use Chamilo\Application\Calendar\Storage\DataClass\Availability;
use Chamilo\Application\Calendar\Storage\DataClass\AvailableCalendar;
use Chamilo\Configuration\Configuration;
use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\DependencyInjection\DependencyInjectionContainerBuilder;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\UserEventParser;

/**
 *
 * @package Chamilo\Application\Calendar\Extension\Personal\Integration\Chamilo\Application\Calendar
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CalendarEventDataProvider extends InternalCalendar
{

    /**
     *
     * @see \Chamilo\Application\Calendar\CalendarInterface::getEvents()
     */
    public function getEvents(
        \Chamilo\Libraries\Calendar\Renderer\Service\CalendarRendererProvider $calendarRendererProvider, 
        $requestedSourceType, $fromDate, $toDate)
    {
        $calendarService = $this->getCalendarService();
        $events = array();
        
        if ($calendarService->isConfigured(Configuration::getInstance()))
        {
            $availabilityService = new AvailabilityService(new AvailabilityRepository());
            $packageContext = ClassnameUtilities::getInstance()->getNamespaceParent(__NAMESPACE__, 4);
            $packageName = ClassnameUtilities::getInstance()->getPackageNameFromNamespace($packageContext);
            
            $activeAvailability = $availabilityService->getAvailabilityByUserAndCalendarTypeAndCalendarIdentifier(
                $calendarRendererProvider->getDataUser(), 
                $packageContext, 
                $packageName);
            
            if (($activeAvailability instanceof Availability && $activeAvailability->getAvailability() == 1) ||
                 ! $activeAvailability instanceof Availability)
            {
                $calendarEvents = $calendarService->getEventsForUserAndBetweenDates(
                    $calendarRendererProvider->getDataUser(), 
                    $fromDate, 
                    $toDate);
                
                foreach ($calendarEvents as $calendarEvent)
                {
                    $eventParser = new UserEventParser(
                        $calendarRendererProvider->getDataUser(), 
                        $calendarEvent, 
                        $fromDate, 
                        $toDate);
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
        $calendars = array();
        
        if ($this->getCalendarService()->isConfigured(Configuration::getInstance()))
        {
            $package = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::package();
            
            $calendar = new AvailableCalendar();
            $calendar->setIdentifier(ClassnameUtilities::getInstance()->getPackageNameFromNamespace($package));
            $calendar->setType($package);
            $calendar->setName(Translation::get('TypeName', null, $package));
            
            $calendars[] = $calendar;
        }
        
        return $calendars;
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService
     */
    public function getCalendarService()
    {
        $container = DependencyInjectionContainerBuilder::getInstance()->createContainer();
        return $container->get('ehb.application.calendar.extension.syllabus_plus.service.calendar_service');
    }
}