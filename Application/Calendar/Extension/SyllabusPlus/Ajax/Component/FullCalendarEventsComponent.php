<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Ajax\Component;

use Symfony\Component\HttpFoundation\JsonResponse;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\UserCalendarRendererProvider;
use Chamilo\Core\User\Storage\DataClass\User;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Ajax\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class FullCalendarEventsComponent extends \Chamilo\Application\Weblcms\Ajax\Manager
{
    const PARAM_USER_USER_ID = 'user_id';

    /**
     *
     * @var \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\UserCalendarRendererProvider
     */
    private $calendarDataProvider;

    private $userCalendar;

    /**
     *
     * @see \Chamilo\Libraries\Architecture\Application\Application::run()
     */
    public function run()
    {
        $calendarDataProvider = $this->getCalendarDataProvider();

        $events = $calendarDataProvider->getAllEvents();

        $eventscollection = array();

        foreach ($events as $event)
        {
            $eventscollection[] = array(
                'title' => $event->getTitle(),
                'url' => $event->getUrl(),
                'start' => date('c', $event->getStartDate()),
                'end' => date('c', $event->getEndDate()));
        }

        // var_dump($eventscollection);
        // exit();

        // $data = json_decode(
        // '[{"title":"All Day Event","start":"2016-12-01"},{"title":"Long
        // Event","start":"2016-12-07","end":"2016-12-10"},{"id":"999","title":"Repeating
        // Event","start":"2016-12-09T16:00:00-05:00"},{"id":"999","title":"Repeating
        // Event","start":"2016-12-16T16:00:00-05:00"},{"title":"Conference","start":"2016-12-11","end":"2016-12-13"},{"title":"Meeting","start":"2016-12-12T10:30:00-05:00","end":"2016-12-12T12:30:00-05:00"},{"title":"Lunch","start":"2016-12-12T12:00:00-05:00"},{"title":"Meeting","start":"2016-12-12T14:30:00-05:00"},{"title":"Happy
        // Hour","start":"2016-12-12T17:30:00-05:00"},{"title":"Dinner","start":"2016-12-12T20:00:00+00:00"},{"title":"Birthday
        // Party","start":"2016-12-13T07:00:00-05:00"},{"url":"http:\/\/google.com\/","title":"Click for
        // Google","start":"2016-12-28"}]');

        $response = new JsonResponse($eventscollection);
        $response->send();
        exit();
    }

    /**
     *
     * @see \Chamilo\Libraries\Architecture\AjaxManager::getRequiredPostParameters()
     */
    public function getRequiredPostParameters()
    {
        return array();
    }

    /**
     *
     * @return \Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider
     */
    protected function getCalendarDataProvider()
    {
        if (! isset($this->calendarDataProvider))
        {
            $this->calendarDataProvider = new UserCalendarRendererProvider(
                $this->getService('ehb.application.calendar.extension.syllabus_plus.service.calendar_service'),
                $this->getUserCalendar(),
                $this->getUser(),
                $this->getDisplayParameters());
        }

        return $this->calendarDataProvider;
    }

    /**
     *
     * @return \Chamilo\Core\User\Storage\DataClass\User
     */
    public function getUserCalendar()
    {
        if (! isset($this->userCalendar))
        {
            $this->setUserCalendar(
                \Chamilo\Core\User\Storage\DataManager::retrieve_by_id(
                    User::class_name(),
                    $this->getUserIdForCalendar()));
        }

        return $this->userCalendar;
    }

    /**
     *
     * @param \Chamilo\Core\User\Storage\DataClass\User $userCalendar
     */
    public function setUserCalendar(User $userCalendar)
    {
        $this->userCalendar = $userCalendar;
    }

    /**
     *
     * @return integer
     */
    public function getUserIdForCalendar()
    {
        $userId = $this->getRequest()->query->get(self::PARAM_USER_USER_ID);

        if (! $userId)
        {
            $userId = 19366;
        }

        return $userId;
    }

    protected function getDisplayParameters()
    {
        return array(
            // self::PARAM_CONTEXT => self::package(),
            // self::PARAM_ACTION => self::ACTION_BROWSE_USER,
            // ViewRenderer::PARAM_TYPE => $this->getCurrentRendererType(),
            // ViewRenderer::PARAM_TIME => $this->getCurrentRendererTime(),
            self::PARAM_USER_USER_ID => $this->getUserCalendar()->get_id());
    }
}