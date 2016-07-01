<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\File\Redirect;

/**
 *
 * @package Chamilo\Application\Calendar\Extension\Personal\Integration\Chamilo\Libraries\Calendar\Event
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class GroupEventParser extends EventParser
{

    /**
     *
     * @param string[] $calendarEvent
     * @return string
     */
    public function getUrl($calendarEvent)
    {
        $parameters = array();
        $parameters[Application::PARAM_CONTEXT] = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::context();
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_ACTION] = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::ACTION_VIEW_GROUP_EVENT;
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_YEAR] = $calendarEvent['year'];
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_GROUP_ID] = $calendarEvent['group_id'];
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_ACTIVITY_ID] = $calendarEvent['id'];
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_ACTIVITY_TIME] = $calendarEvent['start_time'];

        $redirect = new Redirect($parameters);
        return $redirect->getUrl();
    }
}
