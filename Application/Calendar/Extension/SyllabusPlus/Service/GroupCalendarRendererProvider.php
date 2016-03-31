<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

use Chamilo\Configuration\Configuration;
use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\EventParser;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class GroupCalendarRendererProvider extends \Chamilo\Libraries\Calendar\Renderer\Service\CalendarRendererProvider
{

    /**
     *
     * @var string
     */
    private $groupIdentifier;

    /**
     *
     * @param string $groupIdentifier
     * @param \Chamilo\Core\User\Storage\DataClass\User $dataUser
     * @param \Chamilo\Core\User\Storage\DataClass\User $viewingUser
     * @param string[] $displayParameters;
     */
    public function __construct($groupIdentifier, User $dataUser, User $viewingUser, $displayParameters)
    {
        parent :: __construct($dataUser, $viewingUser, $displayParameters);

        $this->groupIdentifier = $groupIdentifier;
    }

    /**
     *
     * @return string
     */
    public function getGroupIdentifier()
    {
        return $this->groupIdentifier;
    }

    /**
     *
     * @param string $groupIdentifier
     */
    public function setGroupIdentifier($groupIdentifier)
    {
        $this->groupIdentifier = $groupIdentifier;
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

        if ($requestedSourceType != self :: SOURCE_TYPE_EXTERNAL)
        {
            // TODO: This is basically almost the same as the logic in the integration with the Calendar application,
            // the logic should therefore be split off into it's own service
            $calendarService = new CalendarService(CalendarRepository :: getInstance());
            $events = array();

            if ($calendarService->isConfigured(Configuration :: get_instance()))
            {
                $eventResultSet = $calendarService->getEventsForGroupAndBetweenDates(
                    $this->getGroupIdentifier(),
                    $startTime,
                    $endTime);

                while ($calenderEvent = $eventResultSet->next_result())
                {
                    $eventParser = new EventParser($this->getDataUser(), $calenderEvent, $startTime, $endTime);
                    $events = array_merge($events, $eventParser->getEvents());
                }
            }
        }

        return $events;
    }
}