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
     * @return the $dataUser
     */
    public function getDataUser()
    {
        return $this->dataUser;
    }

    /**
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

        $parameters = array();
        $parameters[Application :: PARAM_CONTEXT] = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: context();
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: PARAM_ACTION] = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: ACTION_VIEW;
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: PARAM_ACTIVITY_ID] = $calendarEvent['id'];
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: PARAM_ACTIVITY_TIME] = $startTime;
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: PARAM_USER_USER_ID] = $this->getDataUser()->get_id();

        $redirect = new Redirect($parameters);

        $event = new Event(
            $calendarEvent['id'],
            $startTime,
            $endTime,
            new RecurrenceRules(),
            $redirect->getUrl(),
            $this->getEventLabel($calendarEvent),
            $this->getEventLabel($calendarEvent),
            Translation :: get('TypeName', null, \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: context()),
            \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager :: context());

        $event->setCalendarEvent($calendarEvent);

        return array($event);
    }

    private function getEventLabel($calendarEvent)
    {
        $html = array();

        $html[] = $calendarEvent['name'];
//         $html[] = '[' . $calendarEvent['type_code'] . ']';

//         if ($calendarEvent['teacher'])
//         {
//             $html[] = '[' . $calendarEvent['teacher'] . ']';
//         }

//         if ($calendarEvent['location'])
//         {
//             $html[] = '[' . $calendarEvent['location'] . ']';
//         }

        return implode(PHP_EOL, $html);
    }
}
