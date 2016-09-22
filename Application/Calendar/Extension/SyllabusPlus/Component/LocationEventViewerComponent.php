<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\LocationEventParser;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\LocationActivity;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class LocationEventViewerComponent extends EventViewerComponent
{

    /**
     *
     * @var string
     */
    private $locationIdentifier;

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
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getActivityRecord()
     */
    protected function getActivityRecord()
    {
        return $this->getCalendarService()->getEventForLocationByYearAndIdentifier(
            $this->getYear(),
            $this->getLocationIdentifier(),
            $this->getActivityId());
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getModuleEvents()
     */
    protected function getModuleEvents($moduleIdentifier)
    {
        return $this->getCalendarService()->getEventsForLocationByYearAndModuleIdentifier(
            $this->getYear(),
            $this->getLocationIdentifier(),
            $moduleIdentifier);
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getEventParser()
     */
    protected function getEventParser($moduleEvent)
    {
        return new LocationEventParser($this->getUserCalendar(), $moduleEvent, 0, 0);
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getActivityTypeBreadcrumb()
     */
    protected function getActivityTypeBreadcrumb($activityRecord)
    {
        return new Breadcrumb(null, $activityRecord[LocationActivity::PROPERTY_LOCATION_CODE]);
    }
}
