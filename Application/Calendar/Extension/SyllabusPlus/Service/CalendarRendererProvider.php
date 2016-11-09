<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

use Chamilo\Configuration\Configuration;
use Chamilo\Core\User\Storage\DataClass\User;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
abstract class CalendarRendererProvider extends \Chamilo\Libraries\Calendar\Renderer\Service\CalendarRendererProvider
{

    /**
     *
     * @var CalendarService
     */
    private $calendarService;

    /**
     *
     * @var string
     */
    private $year;

    /**
     *
     * @var string
     */
    private $groupIdentifier;

    /**
     *
     * @param CalendarService $calendarService
     * @param \Chamilo\Core\User\Storage\DataClass\User $dataUser
     * @param \Chamilo\Core\User\Storage\DataClass\User $viewingUser
     * @param string[] $displayParameters;
     */
    public function __construct(CalendarService $calendarService, User $dataUser, User $viewingUser, $displayParameters)
    {
        parent::__construct($dataUser, $viewingUser, $displayParameters);
        
        $this->calendarService = $calendarService;
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService
     */
    protected function getCalendarService()
    {
        return $this->calendarService;
    }

    /**
     *
     * @param \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarService $calendarService
     */
    protected function setCalendarService(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     *
     * @param int $sourceType
     * @param integer $startTime
     * @param integer $endTime
     */
    public function aggregateEvents($requestedSourceType, $startTime, $endTime)
    {
        $events = array();
        
        if ($requestedSourceType != self::SOURCE_TYPE_EXTERNAL)
        {
            // TODO: This is basically almost the same as the logic in the integration with the Calendar application,
            // the logic should therefore be split off into it's own service
            $calendarService = $this->getCalendarService();
            $events = array();
            
            if ($calendarService->isConfigured(Configuration::getInstance()))
            {
                $calendarEvents = $this->getCalendarEvents($startTime, $endTime);
                
                foreach ($calendarEvents as $calendarEvent)
                {
                    $eventParser = $this->getEventParser($this->getDataUser(), $calendarEvent, $startTime, $endTime);
                    $events = array_merge($events, $eventParser->getEvents());
                }
            }
        }
        
        return $events;
    }

    /**
     *
     * @param integer $startTime
     * @param integer $endTime
     */
    abstract public function getCalendarEvents($startTime, $endTime);

    /**
     *
     * @param \Chamilo\Core\User\Storage\DataClass\User $dataUser
     * @param string[] $calendarEvent
     * @param integer $startTime
     * @param integer $endTime
     */
    abstract public function getEventParser(User $dataUser, $calendarEvent, $startTime, $endTime);
}