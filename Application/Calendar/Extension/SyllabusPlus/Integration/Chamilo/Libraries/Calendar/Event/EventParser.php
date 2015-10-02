<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event;

use Chamilo\Libraries\Calendar\Event\RecurrenceRules\RecurrenceRules;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Architecture\Application\Application;

/**
 *
 * @package Chamilo\Application\Calendar\Extension\Personal\Integration\Chamilo\Libraries\Calendar\Event
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class EventParser
{

    /**
     *
     * @var string[]
     */
    private $weekLabels;

    /**
     *
     * @var \stdClass
     */
    private $calendarEvent;

    /**
     *
     * @var integer
     */
    private $fromDate;

    /**
     *
     * @var integer
     */
    private $toDate;

    /**
     *
     * @param string[] $weekLabels
     * @param string[] $calendarEvent
     * @param integer $fromDate
     * @param integer $toDate
     */
    public function __construct($weekLabels, $calendarEvent, $fromDate, $toDate)
    {
        $this->weekLabels = $weekLabels;
        $this->calendarEvent = $calendarEvent;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    /**
     *
     * @return string[]
     */
    public function getWeekLabels()
    {
        return $this->weekLabels;
    }

    /**
     *
     * @param string[] $weekLabels
     */
    public function setWeekLabels($weekLabels)
    {
        $this->weekLabels = $weekLabels;
    }

    /**
     *
     * @return \Chamilo\Application\Calendar\Storage\DataClass\AvailableCalendar
     */
    public function getAvailableCalendar()
    {
        return $this->availableCalendar;
    }

    /**
     *
     * @param \Chamilo\Application\Calendar\Storage\DataClass\AvailableCalendar $availableCalendar
     */
    public function setAvailableCalendar(
        \Chamilo\Application\Calendar\Storage\DataClass\AvailableCalendar $availableCalendar)
    {
        $this->availableCalendar = $availableCalendar;
    }

    /**
     *
     * @return \stdClass
     */
    public function getCalendarEvent()
    {
        return $this->calendarEvent;
    }

    /**
     *
     * @param \stdClass $calendarEvent
     */
    public function setCalendarEvent($calendarEvent)
    {
        $this->calendarEvent = $calendarEvent;
    }

    /**
     *
     * @return integer
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     *
     * @param integer $fromDate
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
    }

    /**
     *
     * @return integer
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     *
     * @param integer $toDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
    }

    /**
     *
     * @return \Chamilo\Core\Repository\Integration\Chamilo\Libraries\Calendar\Event\Event[]
     */
    public function getEvents()
    {
        $calendarEvent = $this->getCalendarEvent();

        $events = array();

        $pattern = str_split($calendarEvent['pattern']);
        $weekLabels = $this->getWeekLabels();
        $enabledWeeks = array();

        foreach ($pattern as $weekNumber => $isEnabled)
        {
            if ($isEnabled)
            {
                $startTime = strtotime($calendarEvent['start_time']);
                $baseDate = strtotime($weekLabels[$weekNumber]);
                $baseDate += ($calendarEvent['day'] * 24 * 60 * 60);

                $startDate = mktime(
                    date('G', $startTime),
                    date('i', $startTime),
                    date('s', $startTime),
                    date('n', $baseDate),
                    date('j', $baseDate),
                    date('Y', $baseDate));

                $endDate = $startDate + ($calendarEvent['duration'] * 60);

                $events[] = $this->getEvent($calendarEvent, $startDate, $endDate);
            }
        }

        return $events;
    }

    /**
     *
     * @param string[] $calendarEvent
     * @param integer $startDate
     * @param integer $endDate
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\Event
     */
    private function getEvent($calendarEvent, $startDate, $endDate)
    {
        $parameters = array();
        $parameters[Application :: PARAM_CONTEXT] = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: context();
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: PARAM_ACTION] = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: ACTION_VIEW;
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: PARAM_ACTIVITY_ID] = $calendarEvent['id'];
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: PARAM_ACTIVITY_TIME] = $startDate;

        $redirect = new Redirect($parameters);

        $event = new Event(
            $calendarEvent['id'],
            $startDate,
            $endDate,
            new RecurrenceRules(),
            $redirect->getUrl(),
            $this->getEventLabel($calendarEvent),
            $this->getEventLabel($calendarEvent),
            Translation :: get('TypeName', null, \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: context()),
            \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: context());

        $event->setCalendarEvent($calendarEvent);

        return $event;
    }

    private function getEventLabel($calendarEvent)
    {
        $html = array();

        $html[] = $calendarEvent['name'];
        $html[] = '[' . $calendarEvent['type_code'] . ']';

        if ($calendarEvent['teacher'])
        {
            $html[] = '[' . $calendarEvent['teacher'] . ']';
        }

        if ($calendarEvent['location'])
        {
            $html[] = '[' . $calendarEvent['location'] . ']';
        }

        return implode(PHP_EOL, $html);
    }
}
