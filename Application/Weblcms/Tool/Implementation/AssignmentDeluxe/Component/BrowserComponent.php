<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\AssignmentDeluxe\Component;

use Chamilo\Application\Weblcms\Storage\DataClass\ContentObjectPublication;
use Chamilo\Core\Repository\ContentObject\Assignment\Storage\DataClass\Assignment;
use Chamilo\Core\Repository\ContentObject\CalendarEvent\Storage\DataClass\CalendarEvent;
use Chamilo\Core\Repository\Storage\DataClass\ContentObject;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Weblcms\Tool\Implementation\AssignmentDeluxe\Manager;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\AssignmentDeluxe\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class BrowserComponent extends Manager implements DelegateComponent
{

    public function convert_content_object_publication_to_calendar_event($publication, $from_time, $to_time)
    {
        $object = \Chamilo\Core\Repository\Storage\DataManager :: retrieve_by_id(
            Assignment :: class_name(),
            $publication[ContentObjectPublication :: PROPERTY_CONTENT_OBJECT_ID]);

        $calendar_event = ContentObject :: factory(CalendarEvent :: class_name());

        $calendar_event->set_title($object->get_title());
        $calendar_event->set_description($object->get_description());
        if ($object instanceof Assignment)
        {
            $calendar_event->set_start_date($object->get_start_time());
            $calendar_event->set_end_date($object->get_end_time());
        }
        else
        {
            $calendar_event->set_start_date($object->get_start_date());
            $calendar_event->set_end_date($object->get_end_date());
        }

        $calendar_event->set_frequency(CalendarEvent :: FREQUENCY_NONE);

        return $calendar_event;
    }
}