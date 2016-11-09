<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Chamilo\Libraries\Format\Table\PropertiesTable;
use Chamilo\Libraries\Format\Table\SortableTableFromArray;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\ResultSet\ArrayResultSet;
use Chamilo\Libraries\Utilities\DatetimeUtilities;
use Chamilo\Libraries\Utilities\StringUtilities;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\Activity;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
abstract class EventViewerComponent extends Manager
{

    /**
     *
     * @var string
     */
    private $year;

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $activityId = $this->getActivityId();

        if ($activityId)
        {
            $activityHtml = $this->getActivityAsHtml($this->getActivityRecord(), $this->getActivityTime());

            $html = array();

            $html[] = $this->render_header();
            $html[] = $activityHtml;
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
        else
        {
            return $this->display_error_page(htmlentities(Translation::get('NoActivitySelected')));
        }
    }

    abstract protected function getActivityRecord();

    /**
     *
     * @return string
     */
    protected function getActivityId()
    {
        return $this->getRequest()->query->get(Manager::PARAM_ACTIVITY_ID);
    }

    /**
     *
     * @return integer
     */
    protected function getActivityTime()
    {
        return $this->getRequest()->query->get(Manager::PARAM_ACTIVITY_TIME);
    }

    /**
     *
     * @return string
     */
    protected function getYear()
    {
        if (! isset($this->year))
        {
            $this->year = $this->getRequest()->query->get(self::PARAM_YEAR);
        }

        return $this->year;
    }

    /**
     *
     * @param string[] $activityRecord
     * @param integer $activityTime
     * @return string
     */
    protected function getActivityAsHtml($activityRecord, $activityTime)
    {
        BreadcrumbTrail::getInstance()->add(
            new Breadcrumb(null, Translation::get('AcademicYear', array('YEAR' => $this->getYear()))));
        BreadcrumbTrail::getInstance()->add($this->getActivityTypeBreadcrumb($activityRecord));
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, $activityRecord[Activity::PROPERTY_NAME]));

        return $this->renderInformation($activityRecord, $activityTime);
    }

    protected function getHighlightedEvent($activityEvents, $activityTime)
    {
        if (count($activityEvents) == 1)
        {
            $highlightedEvent = array_pop($activityEvents);
        }
        elseif ($activityTime)
        {
            $highlightedEvent = $activityEvents[$activityTime];
        }
        else
        {
            $highlightedEvent = $activityEvents[key($activityEvents)];
        }

        return $highlightedEvent;
    }

    /**
     *
     * @param string[] $activityRecord
     * @param integer $activityTime
     * @return string
     */
    protected function renderInformation($activityRecord, $activityTime = null)
    {
        $properties = array();

        $properties[Translation::get('ActivityType')] = $activityRecord[Activity::PROPERTY_TYPE];

        $activityEvents = $this->getEvents($activityRecord);
        $highlightedEvent = $this->getHighlightedEvent($activityEvents, $activityTime);

        $dateDay = DatetimeUtilities::format_locale_date('%A %d %B %Y', $highlightedEvent->getStartDate());
        $dateStart = DatetimeUtilities::format_locale_date('%H:%M', $highlightedEvent->getStartDate());
        $dateEnd = DatetimeUtilities::format_locale_date('%H:%M', $highlightedEvent->getEndDate());

        $properties[Translation::get('OnDate')] = Translation::get(
            'ActivityDateValue',
            array('DAY' => $dateDay, 'FROM' => $dateStart, 'UNTIL' => $dateEnd));

        if ($activityRecord[Activity::PROPERTY_LOCATION])
        {
            $properties[Translation::get('AtLocation')] = $activityRecord[Activity::PROPERTY_LOCATION];
        }

        if ($activityRecord[Activity::PROPERTY_STUDENT_GROUP])
        {
            $properties[Translation::get('ForGroups')] = $activityRecord[Activity::PROPERTY_STUDENT_GROUP];
        }

        if (! StringUtilities::getInstance()->createString($activityRecord[Activity::PROPERTY_TEACHER])->isBlank())
        {
            $properties[Translation::get('ByTeacher')] = $activityRecord[Activity::PROPERTY_TEACHER];
        }

        $propertiesTable = new PropertiesTable($properties);

        $html = array();

        $html[] = $propertiesTable->toHtml();

        if (count($activityEvents) > 1)
        {
            $pastEvents = array();
            $currentEvents = array();

            foreach ($activityEvents as $event)
            {
                $isPastEvent = ($event->getEndDate() < time());

                if ($isPastEvent)
                {
                    $pastEvents[] = $event;
                }
                else
                {
                    $currentEvents[] = $event;
                }
            }

            if (count($currentEvents) > 0)
            {
                $html[] = '<br />';
                $html[] = '<h4>' . Translation::get('CurrentUpcomingCourseMoments') . '</h4>';
                $html[] = $this->renderOccurrences($currentEvents);
            }

            if (count($pastEvents) > 0)
            {
                $html[] = '<br />';
                $html[] = '<h4>' . Translation::get('PreviousCourseMoments') . '</h4>';
                $html[] = $this->renderOccurrences($pastEvents);
            }
        }

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @param string[] $activityRecord
     * @return \Chamilo\Core\Repository\Integration\Chamilo\Libraries\Calendar\Event\Event[]
     */
    protected function getEvents($activityRecord)
    {
        $sortedEvents = array();

        if (! is_null($activityRecord[Activity::PROPERTY_MODULE_ID]))
        {
            $moduleEvents = $this->getModuleEvents($activityRecord[Activity::PROPERTY_MODULE_ID]);

            foreach ($moduleEvents as $moduleEvent)
            {

                $eventParser = $this->getEventParser($moduleEvent);

                foreach ($eventParser->getEvents() as $event)
                {
                    $sortedEvents[$event->getStartDate()] = $event;
                }
            }

            ksort($sortedEvents);
        }
        else
        {
            $eventParser = $this->getEventParser($activityRecord);

            foreach ($eventParser->getEvents() as $event)
            {
                $sortedEvents[$event->getStartDate()] = $event;
            }
        }

        return $sortedEvents;
    }

    /**
     *
     * @param string $moduleIdentifier
     * @return ArrayResultSet
     */
    abstract protected function getModuleEvents($moduleIdentifier);

    /**
     *
     * @param string[] $moduleEvent
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\EventParser
     */
    abstract protected function getEventParser($moduleEvent);

    /**
     *
     * @param \Chamilo\Core\Repository\Integration\Chamilo\Libraries\Calendar\Event\Event[] $events
     * @return string
     */
    protected function renderOccurrences($events)
    {
        $tableData = array();

        $hasTeachers = false;

        foreach ($events as $event)
        {
            $activityRecord = $event->getCalendarEvent();

            if (! StringUtilities::getInstance()->createString($activityRecord[Activity::PROPERTY_TEACHER])->isBlank())
            {
                $hasTeachers = true;
                break;
            }
        }

        foreach ($events as $event)
        {

            $isPastEvent = ($event->getEndDate() < time());
            $isCurrentEvent = ($event->getStartDate() <= time() && $event->getEndDate() >= time());

            if ($isPastEvent)
            {
                $class = ' class="text-danger"';
            }
            elseif ($isCurrentEvent)
            {
                $class = ' class="text-primary"';
            }
            else
            {
                $class = '';
            }

            $activityRecord = $event->getCalendarEvent();

            $tableRow = array();

            $tableRow[] = '<span' . $class . '>' . $activityRecord[Activity::PROPERTY_TYPE] . '</span>';

            $startDate = DatetimeUtilities::format_locale_date('%A %d %B %Y', $event->getStartDate());

            $startTime = DatetimeUtilities::format_locale_date('%H:%M', $event->getStartDate());
            $endTime = DatetimeUtilities::format_locale_date('%H:%M', $event->getEndDate());

            $tableRow[] = '<span' . $class . '>' . $startDate . '</span>';
            $tableRow[] = '<span' . $class . '>' . $startTime . '</span>';
            $tableRow[] = '<span' . $class . '>' . $endTime . '</span>';

            $tableRow[] = '<span' . $class . '>' . $activityRecord[Activity::PROPERTY_LOCATION] . '</span>';
            $tableRow[] = '<span' . $class . '>' . $activityRecord[Activity::PROPERTY_STUDENT_GROUP] . '</span>';

            if ($hasTeachers)
            {
                $tableRow[] = '<span' . $class . '>' . $activityRecord[Activity::PROPERTY_TEACHER] . '</span>';
            }

            $tableData[] = $tableRow;
        }

        $headers = array();
        $headers[] = new StaticTableColumn(Translation::get('ActivityType'));
        $headers[] = new StaticTableColumn(Translation::get('OnDate'));
        $headers[] = new StaticTableColumn(Translation::get('FromTime'));
        $headers[] = new StaticTableColumn(Translation::get('ToTime'));
        $headers[] = new StaticTableColumn(Translation::get('AtLocation'));
        $headers[] = new StaticTableColumn(Translation::get('ForGroups'));

        if ($hasTeachers)
        {
            $headers[] = new StaticTableColumn(Translation::get('ByTeacher'));
        }

        $parameters = array();
        $parameters[Application::PARAM_CONTEXT] = self::package();
        $parameters[self::PARAM_ACTION] = self::ACTION_VIEW_USER_EVENT;
        $parameters[self::PARAM_USER_USER_ID] = $this->getUserCalendar()->getId();
        $parameters[self::PARAM_ACTIVITY_ID] = $this->getActivityId();
        $parameters[self::PARAM_ACTIVITY_TIME] = $this->getActivityTime();

        $table = new SortableTableFromArray(
            $tableData,
            $headers,
            $parameters,
            1,
            10,
            SORT_DESC,
            'activity',
            false,
            false,
            false);

        return $table->toHtml();
    }

    /**
     *
     * @param string[] $activityRecord
     * @return \Chamilo\Libraries\Format\Structure\Breadcrumb
     */
    abstract protected function getActivityTypeBreadcrumb($activityRecord);
}
