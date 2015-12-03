<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\AssignmentDeluxe;

use Chamilo\Application\Weblcms\Renderer\PublicationList\ContentObjectPublicationListRenderer;
use Chamilo\Core\Repository\ContentObject\Assignment\Storage\DataClass\Assignment;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\AssignmentDeluxe
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Manager extends \Chamilo\Application\Weblcms\Tool\Manager
{

    /**
     *
     * @see \Chamilo\Application\Weblcms\Tool\Manager::get_available_browser_types()
     */
    public function get_available_browser_types()
    {
        $browserTypes = array();

        $browserTypes[] = ContentObjectPublicationListRenderer :: TYPE_TABLE;
        $browserTypes[] = ContentObjectPublicationListRenderer :: TYPE_LIST;
        $browserTypes[] = ContentObjectPublicationListRenderer :: TYPE_CALENDAR;

        return $browserTypes;
    }

    /**
     *
     * @return string[]
     */
    public static function get_allowed_types()
    {
        return array(Assignment :: class_name());
    }
}
