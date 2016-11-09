<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\UserEventParser;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class UserEventViewerComponent extends EventViewerComponent implements DelegateComponent
{

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getActivityRecord()
     */
    protected function getActivityRecord()
    {
        return $this->getCalendarService()->getEventForUserByIdentifier(
            $this->getUserCalendar(), 
            $this->getActivityId(), 
            $this->getYear());
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getModuleEvents()
     */
    protected function getModuleEvents($moduleIdentifier)
    {
        return $this->getCalendarService()->getEventsForUserByModuleIdentifier(
            $this->getUserCalendar(), 
            $moduleIdentifier, 
            $this->getYear());
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getEventParser()
     */
    protected function getEventParser($moduleEvent)
    {
        return new UserEventParser($this->getUserCalendar(), $moduleEvent, 0, 0);
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getActivityTypeBreadcrumb()
     */
    protected function getActivityTypeBreadcrumb($activityRecord)
    {
        return new Breadcrumb(null, $this->getUserCalendar()->get_fullname());
    }
}
