<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Chamilo\Libraries\Format\Table\PropertiesTable;
use Chamilo\Libraries\Format\Table\SortableTableFromArray;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\DatetimeUtilities;
use Chamilo\Libraries\Utilities\StringUtilities;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\EventParser;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService;

/**
 *
 * @package application\calendar
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class ViewerComponent extends Manager implements DelegateComponent
{

    /**
     *
     * @var \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService
     */
    private $calendarService;

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $time = Request :: get(\Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer :: PARAM_TIME) ? intval(
            Request :: get(\Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer :: PARAM_TIME)) : time();
        $view = Request :: get(\Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer :: PARAM_TYPE) ? Request :: get(
            \Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer :: PARAM_TYPE) : \Chamilo\Libraries\Calendar\Renderer\Type\ViewRenderer :: TYPE_MONTH;

        $activityId = $this->getActivityId();
        $activityTime = $this->getActivityTime();

        if ($activityId)
        {
            $activityRecord = $this->getCalendarService()->getEventForUserByIdentifier(
                $this->getUserCalendar(),
                $activityId);
            $activityHtml = $this->getActivityAsHtml($activityRecord, $activityTime);

            $html = array();

            $html[] = $this->render_header();
            $html[] = $activityHtml;
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
        else
        {
            return $this->display_error_page(htmlentities(Translation :: get('NoActivitySelected')));
        }
    }

    /**
     *
     * @return string
     */
    private function getActivityId()
    {
        return Request :: get(Manager :: PARAM_ACTIVITY_ID);
    }

    /**
     *
     * @return integer
     */
    private function getActivityTime()
    {
        return Request :: get(Manager :: PARAM_ACTIVITY_TIME);
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService
     */
    public function getCalendarService()
    {
        if (! isset($this->calendarService))
        {
            $this->calendarService = new CalendarService(CalendarRepository :: getInstance());
        }

        return $this->calendarService;
    }

    /**
     *
     * @param string[] $activityRecord
     * @param integer $activityTime
     * @return string
     */
    public function getActivityAsHtml($activityRecord, $activityTime)
    {
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $activityRecord['name']));

        $html = array();

        return $this->renderInformation($activityRecord, $activityTime);
    }

    public function getHighlightedEvent($activityEvents, $activityTime)
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
    public function renderInformation($activityRecord, $activityTime = null)
    {
        $properties = array();

        $properties[Translation :: get('ActivityType')] = $activityRecord['type'];

        $activityEvents = $this->getEvents($activityRecord);
        $highlightedEvent = $this->getHighlightedEvent($activityEvents, $activityTime);

        $dateDay = DatetimeUtilities :: format_locale_date('%A %d %B %Y', $highlightedEvent->getStartDate());
        $dateStart = DatetimeUtilities :: format_locale_date('%H:%M', $highlightedEvent->getStartDate());
        $dateEnd = DatetimeUtilities :: format_locale_date('%H:%M', $highlightedEvent->getEndDate());

        $properties[Translation :: get('OnDate')] = Translation :: get(
            'ActivityDateValue',
            array('DAY' => $dateDay, 'FROM' => $dateStart, 'UNTIL' => $dateEnd));

        if ($activityRecord['location'])
        {
            $properties[Translation :: get('AtLocation')] = $activityRecord['location'];
        }

        if ($activityRecord['groups'])
        {
            $properties[Translation :: get('ForGroups')] = $activityRecord['groups'];
        }

        if (! StringUtilities :: getInstance()->createString($activityRecord['teacher'])->isBlank())
        {
            $properties[Translation :: get('ByTeacher')] = $activityRecord['teacher'];
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
                $html[] = '<h4>' . Translation :: get('CurrentUpcomingCourseMoments') . '</h4>';
                $html[] = $this->renderOccurrences($currentEvents);
            }

            if (count($pastEvents) > 0)
            {
                $html[] = '<br />';
                $html[] = '<h4>' . Translation :: get('PreviousCourseMoments') . '</h4>';
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
    private function getEvents($activityRecord)
    {
        $moduleEvents = $this->getCalendarService()->getEventsForUserByModuleIdentifier(
            $this->getUserCalendar(),
            $activityRecord['module_id']);

        $sortedEvents = array();

        while ($moduleEvent = $moduleEvents->next_result())
        {
            $eventParser = new EventParser($this->getUserCalendar(), $moduleEvent, 0, 0);

            foreach ($eventParser->getEvents() as $event)
            {
                $sortedEvents[$event->getStartDate()] = $event;
            }
        }

        ksort($sortedEvents);

        return $sortedEvents;
    }

    /**
     *
     * @param \Chamilo\Core\Repository\Integration\Chamilo\Libraries\Calendar\Event\Event[] $events
     * @return string
     */
    public function renderOccurrences($events)
    {
        $tableData = array();

        $hasTeachers = false;

        foreach ($events as $event)
        {
            $activityRecord = $event->getCalendarEvent();

            if (! StringUtilities :: getInstance()->createString($activityRecord['teacher'])->isBlank())
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
                $class = ' class="event-expired"';
            }
            elseif ($isCurrentEvent)
            {
                $class = ' class="event-current"';
            }
            else
            {
                $class = '';
            }

            $activityRecord = $event->getCalendarEvent();

            $tableRow = array();

            $tableRow[] = '<span' . $class . '>' . $activityRecord['type'] . '</span>';

            $startDate = DatetimeUtilities :: format_locale_date('%A %d %B %Y', $event->getStartDate());

            $startTime = DatetimeUtilities :: format_locale_date('%H:%M', $event->getStartDate());
            $endTime = DatetimeUtilities :: format_locale_date('%H:%M', $event->getEndDate());

            $tableRow[] = '<span' . $class . '>' . $startDate . '</span>';
            $tableRow[] = '<span' . $class . '>' . $startTime . '</span>';
            $tableRow[] = '<span' . $class . '>' . $endTime . '</span>';

            $tableRow[] = '<span' . $class . '>' . $activityRecord['location'] . '</span>';
            $tableRow[] = '<span' . $class . '>' . $activityRecord['groups'] . '</span>';

            if ($hasTeachers)
            {
                $tableRow[] = '<span' . $class . '>' . $activityRecord['teacher'] . '</span>';
            }

            $tableData[] = $tableRow;
        }

        $headers = array();
        $headers[] = new StaticTableColumn(Translation :: get('ActivityType'));
        $headers[] = new StaticTableColumn(Translation :: get('OnDate'));
        $headers[] = new StaticTableColumn(Translation :: get('FromTime'));
        $headers[] = new StaticTableColumn(Translation :: get('ToTime'));
        $headers[] = new StaticTableColumn(Translation :: get('AtLocation'));
        $headers[] = new StaticTableColumn(Translation :: get('ForGroups'));

        if ($hasTeachers)
        {
            $headers[] = new StaticTableColumn(Translation :: get('ByTeacher'));
        }

        $parameters = array();
        $parameters[Application :: PARAM_CONTEXT] = self :: package();
        $parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;
        $parameters[self :: PARAM_USER_USER_ID] = $this->getUserCalendar()->getId();
        $parameters[self :: PARAM_ACTIVITY_ID] = $this->getActivityId();
        $parameters[self :: PARAM_ACTIVITY_TIME] = $this->getActivityTime();

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
}
