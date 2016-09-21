<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event;

use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Calendar\Event\RecurrenceRules\RecurrenceRules;
use Chamilo\Libraries\Platform\Translation;

/**
 *
 * @package Chamilo\Application\Calendar\Extension\Personal\Integration\Chamilo\Libraries\Calendar\Event
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
abstract class EventParser
{

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

    private $dataUser;

    /**
     *
     * @param User $dataUser
     * @param string[] $calendarEvent
     * @param integer $fromDate
     * @param integer $toDate
     */
    public function __construct($dataUser, $calendarEvent, $fromDate, $toDate)
    {
        $this->dataUser = $dataUser;
        $this->calendarEvent = $calendarEvent;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    /**
     *
     * @return the $dataUser
     */
    public function getDataUser()
    {
        return $this->dataUser;
    }

    /**
     *
     * @param field_type $dataUser
     */
    public function setDataUser($dataUser)
    {
        $this->dataUser = $dataUser;
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

        $startTime = strtotime($calendarEvent['start_time']);
        $endTime = strtotime($calendarEvent['end_time']);

        $source = '[' . $calendarEvent['type_code'] . '] ' . $calendarEvent['type'];

        $event = new Event(
            $calendarEvent['id'],
            $startTime,
            $endTime,
            new RecurrenceRules(),
            $this->getUrl($calendarEvent),
            $this->getEventLabel($calendarEvent),
            $this->getEventDescription($calendarEvent),
            $this->getLocationFromCalendarEvent($calendarEvent),
            $source,
            \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::context());

        $event->setCalendarEvent($calendarEvent);

        return array($event);
    }

    protected function getLocationFromCalendarEvent($calendarEvent)
    {
        return $calendarEvent['location'];
    }

    /**
     *
     * @param string[] $calendarEvent
     * @return string
     */
    private function getEventLabel($calendarEvent)
    {
        $html = array();

        $html[] = '[' . $calendarEvent['type_code'] . ']';
        $html[] = $calendarEvent['name'];

        if ($calendarEvent['location'])
        {
            $html[] = '(' . trim($calendarEvent['location']) . ')';
        }

        if ($this->getDataUser()->get_status() == User::STATUS_TEACHER)
        {
            if ($calendarEvent['student_group'])
            {
                $html[] = '(' . trim($calendarEvent['student_group']) . ')';
            }
        }

        return implode(' ', $html);
    }

    /**
     *
     * @param string[] $calendarEvent
     * @return string
     */
    private function getEventDescription($calendarEvent)
    {
        $html = array();

        $html[] = $calendarEvent['type'];

        if ($calendarEvent['teacher'])
        {
            $html[] = Translation::get('ByTeacher') . ' ' . trim($calendarEvent['teacher']);
        }

        if ($calendarEvent['location'])
        {
            $html[] = Translation::get('AtLocation') . ' ' . trim($calendarEvent['location']);
        }

        if ($calendarEvent['student_group'])
        {
            $html[] = Translation::get('ForGroups') . ' ' . trim($calendarEvent['student_group']);
        }

        return implode(PHP_EOL, $html);
    }

    /**
     *
     * @param string[] $calendarEvent
     * @return string
     */
    abstract function getUrl($calendarEvent);
}
