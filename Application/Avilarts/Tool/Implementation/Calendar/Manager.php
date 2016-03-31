<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Calendar;

use Chamilo\Core\Repository\ContentObject\CalendarEvent\Storage\DataClass\CalendarEvent;
use Ehb\Application\Avilarts\Renderer\PublicationList\ContentObjectPublicationListRenderer;

abstract class Manager extends \Ehb\Application\Avilarts\Tool\Manager
{

    public static function get_allowed_types()
    {
        return array(CalendarEvent :: class_name());
    }

    public function get_available_browser_types()
    {
        $browser_types = array();
        $browser_types[] = ContentObjectPublicationListRenderer :: TYPE_CALENDAR;
        $browser_types[] = ContentObjectPublicationListRenderer :: TYPE_LIST;
        $browser_types[] = ContentObjectPublicationListRenderer :: TYPE_TABLE;
        return $browser_types;
    }
}