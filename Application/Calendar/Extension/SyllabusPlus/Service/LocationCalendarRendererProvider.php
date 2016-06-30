<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

use Chamilo\Configuration\Configuration;
use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\LocationEventParser;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class LocationCalendarRendererProvider extends \Chamilo\Libraries\Calendar\Renderer\Service\CalendarRendererProvider
{

    /**
     *
     * @var string
     */
    private $year;

    /**
     *
     * @var string
     */
    private $locationIdentifier;

    /**
     *
     * @param string $year
     * @param string $locationIdentifier
     * @param \Chamilo\Core\User\Storage\DataClass\User $dataUser
     * @param \Chamilo\Core\User\Storage\DataClass\User $viewingUser
     * @param string[] $displayParameters;
     */
    public function __construct($year, $locationIdentifier, User $dataUser, User $viewingUser, $displayParameters)
    {
        parent::__construct($dataUser, $viewingUser, $displayParameters);

        $this->year = $year;
        $this->locationIdentifier = $locationIdentifier;
    }

    /**
     *
     * @return string
     */
    public function getLocationIdentifier()
    {
        return $this->locationIdentifier;
    }

    /**
     *
     * @param string $locationIdentifier
     */
    public function setLocationIdentifier($locationIdentifier)
    {
        $this->locationIdentifier = $locationIdentifier;
    }

    /**
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     *
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
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
            $calendarService = new CalendarService(CalendarRepository::getInstance());
            $events = array();

            if ($calendarService->isConfigured(Configuration::get_instance()))
            {
                $eventResultSet = $calendarService->getEventsByYearAndLocationAndBetweenDates(
                    $this->getYear(),
                    $this->getLocationIdentifier(),
                    $startTime,
                    $endTime);

                while ($calenderEvent = $eventResultSet->next_result())
                {
                    $eventParser = new LocationEventParser($this->getDataUser(), $calenderEvent, $startTime, $endTime);
                    $events = array_merge($events, $eventParser->getEvents());
                }
            }
        }

        return $events;
    }
}