<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Integration\Chamilo\Libraries\Calendar\Event;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\File\Redirect;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass\LocationActivity;

/**
 *
 * @package Chamilo\Application\Calendar\Extension\Personal\Integration\Chamilo\Libraries\Calendar\Event
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class LocationEventParser extends EventParser
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
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_ACTION] = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::ACTION_VIEW_LOCATION_EVENT;
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_YEAR] = $calendarEvent[LocationActivity::PROPERTY_YEAR];
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_LOCATION_ID] = $calendarEvent[LocationActivity::PROPERTY_LOCATION_ID];
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_ACTIVITY_ID] = $calendarEvent[LocationActivity::PROPERTY_ID];
        $parameters[\Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_ACTIVITY_TIME] = strtotime(
            $calendarEvent[LocationActivity::PROPERTY_START_TIME]);
        
        $redirect = new Redirect($parameters);
        return $redirect->getUrl();
    }

    protected function getLocationFromCalendarEvent($calendarEvent)
    {
        return $calendarEvent[LocationActivity::PROPERTY_LOCATION_CODE];
    }
}
