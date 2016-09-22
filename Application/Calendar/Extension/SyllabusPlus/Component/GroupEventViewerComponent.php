<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\GroupEventParser;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\GroupActivity;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class GroupEventViewerComponent extends EventViewerComponent
{

    /**
     *
     * @var string
     */
    private $groupIdentifier;

    /**
     *
     * @return string
     */
    protected function getGroupIdentifier()
    {
        if (! isset($this->groupIdentifier))
        {
            $this->groupIdentifier = $this->getRequest()->query->get(self::PARAM_GROUP_ID);
        }

        return $this->groupIdentifier;
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getActivityRecord()
     */
    protected function getActivityRecord()
    {
        return $this->getCalendarService()->getEventForGroupByYearAndIdentifier(
            $this->getYear(),
            $this->getGroupIdentifier(),
            $this->getActivityId());
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getModuleEvents()
     */
    protected function getModuleEvents($moduleIdentifier)
    {
        return $this->getCalendarService()->getEventsForGroupByYearAndModuleIdentifier(
            $this->getYear(),
            $this->getGroupIdentifier(),
            $moduleIdentifier);
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getEventParser()
     */
    protected function getEventParser($moduleEvent)
    {
        return new GroupEventParser($this->getUserCalendar(), $moduleEvent, 0, 0);
    }

    /**
     *
     * @see \Ehb\Application\Calendar\Extension\SyllabusPlus\Component\EventViewerComponent::getActivityTypeBreadcrumb()
     */
    protected function getActivityTypeBreadcrumb($activityRecord)
    {
        return new Breadcrumb(null, $activityRecord[GroupActivity::PROPERTY_GROUP_NAME]);
    }
}
