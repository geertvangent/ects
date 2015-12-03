<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Service;

use Chamilo\Application\Calendar\Repository\CalendarRendererProviderRepository;
use Chamilo\Configuration\Configuration;
use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event\EventParser;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Repository\CalendarRepository;

/**
 *
 * @package Chamilo\Application\Calendar\Service
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class CalendarRendererProvider extends \Chamilo\Libraries\Calendar\Renderer\Service\CalendarRendererProvider
{

    /**
     *
     * @var \Chamilo\Application\Calendar\Extension\Personal\Integration\Chamilo\Application\Calendar\Repository\CalendarRendererProviderRepository
     */
    private $dataProviderRepository;

    /**
     *
     * @var string
     */
    private $visibilityContext;

    /**
     *
     * @param \Chamilo\Application\Calendar\Extension\Personal\Integration\Chamilo\Application\Calendar\Repository\CalendarRendererProviderRepository $dataProviderRepository
     * @param \Chamilo\Core\User\Storage\DataClass\User $dataUser
     * @param \Chamilo\Core\User\Storage\DataClass\User $viewingUser
     * @param string[] $displayParameters;
     * @param string $visibilityContext
     */
    public function __construct(CalendarRendererProviderRepository $dataProviderRepository, User $dataUser,
        User $viewingUser, $displayParameters)
    {
        $this->dataProviderRepository = $dataProviderRepository;

        parent :: __construct($dataUser, $viewingUser, $displayParameters);
    }

    /**
     *
     * @return \Chamilo\Application\Calendar\Extension\Personal\Integration\Chamilo\Application\Calendar\Repository\CalendarRendererProviderRepository
     */
    public function getCalendarRendererProviderRepository()
    {
        return $this->dataProviderRepository;
    }

    /**
     *
     * @param \Chamilo\Application\Calendar\Extension\Personal\Integration\Chamilo\Application\Calendar\Repository\CalendarRendererProviderRepository $dataProviderRepository
     */
    public function setCalendarRendererProviderRepository(CalendarRendererProviderRepository $dataProviderRepository)
    {
        $this->dataProviderRepository = $dataProviderRepository;
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
                $eventResultSet = $calendarService->getEventsForUserAndBetweenDates(
                    $this->getDataUser(),
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