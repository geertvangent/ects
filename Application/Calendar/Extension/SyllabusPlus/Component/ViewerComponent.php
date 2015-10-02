<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;
use Chamilo\Libraries\Format\Table\PropertiesTable;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\EventParser;
use Chamilo\Libraries\Format\Table\SortableTableFromArray;
use Chamilo\Libraries\Utilities\DatetimeUtilities;

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

        $activityId = Request :: get(Manager :: PARAM_ACTIVITY_ID);

        if ($activityId)
        {
            $activityRecord = $this->getCalendarService()->getEventForUserByIdentifier($this->getUser(), $activityId);
            $activityHtml = $this->getActivityAsHtml($activityRecord);

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
     * @return string
     */
    public function getActivityAsHtml($activityRecord)
    {
        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $activityRecord['name']));

        $html = array();

        return $this->renderInformation($activityRecord);
    }

    /**
     *
     * @param string[] $activityRecord
     * @return string
     */
    public function renderInformation($activityRecord)
    {
        $properties = array();

        $properties[Translation :: get('ActivityType')] = $activityRecord['type'];
        $properties[Translation :: get('ActivityTeacher')] = $activityRecord['teacher'];
        $properties[Translation :: get('ActivityLocation')] = $activityRecord['location'];

        $events = $this->getEvents($activityRecord);

        if (count($events) == 1)
        {
            $singleEvent = array_pop($events);

            $dateDay = DatetimeUtilities :: format_locale_date('%A %d %B %Y', $singleEvent->getStartDate());
            $dateStart = DatetimeUtilities :: format_locale_date('%H:%M', $singleEvent->getStartDate());
            $dateEnd = DatetimeUtilities :: format_locale_date('%H:%M', $singleEvent->getEndDate());

            $properties[Translation :: get('ActivityDate')] = Translation :: get(
                'ActivityDateValue',
                array('DAY' => $dateDay, 'FROM' => $dateStart, 'UNTIL' => $dateEnd));
        }

        $propertiesTable = new PropertiesTable($properties);

        $html = array();

        $html[] = $propertiesTable->toHtml();

        if (count($events) > 1)
        {
            $html[] = '<br />';
            $html[] = $this->renderOccurrences($events);
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
        $weekLabels = $this->getCalendarService()->getWeekLabels();

        $eventParser = new EventParser(
            $weekLabels,
            $activityRecord,
            strtotime($weekLabels[0]),
            strtotime($weekLabels[51]));

        return $eventParser->getEvents();
    }

    /**
     *
     * @param \Chamilo\Core\Repository\Integration\Chamilo\Libraries\Calendar\Event\Event[] $events
     * @return string
     */
    public function renderOccurrences($events)
    {
        $tableData = array();

        foreach ($events as $event)
        {
            $tableRow = array();

            $tableRow[] = DatetimeUtilities :: format_locale_date('%A %d %B %Y', $event->getStartDate());
            $tableRow[] = DatetimeUtilities :: format_locale_date('%H:%M', $event->getStartDate());
            $tableRow[] = DatetimeUtilities :: format_locale_date('%H:%M', $event->getEndDate());

            $tableData[] = $tableRow;
        }

        $table = new SortableTableFromArray($tableData, 1, 10, 'activity', SORT_DESC, false, false, false);

        $table->set_header(0, Translation :: get('OnDate'), false);
        $table->set_header(1, Translation :: get('FromTime'), false);
        $table->set_header(2, Translation :: get('ToTime'), false);

        return $table->as_html();
    }
}
