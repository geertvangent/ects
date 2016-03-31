<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\StreamingVideo;

use Chamilo\Core\Repository\ContentObject\Matterhorn\Storage\DataClass\Matterhorn;
use Chamilo\Core\Repository\ContentObject\Vimeo\Storage\DataClass\Vimeo;
use Chamilo\Core\Repository\ContentObject\Youtube\Storage\DataClass\Youtube;
use Chamilo\Libraries\Architecture\Interfaces\Categorizable;
use Ehb\Application\Avilarts\Renderer\PublicationList\ContentObjectPublicationListRenderer;

/**
 * $Id: announcement_tool.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.announcement.component
 */

/**
 * This tool allows a user to publish announcements in his or her course.
 */
abstract class Manager extends \Ehb\Application\Avilarts\Tool\Manager implements Categorizable
{

    public static function get_allowed_types()
    {
        return array(Youtube :: class_name(), Vimeo :: class_name(), Matterhorn :: class_name());
    }

    public function get_available_browser_types()
    {
        $browser_types = array();
        $browser_types[] = ContentObjectPublicationListRenderer :: TYPE_TABLE;
        $browser_types[] = ContentObjectPublicationListRenderer :: TYPE_GALLERY;
        $browser_types[] = ContentObjectPublicationListRenderer :: TYPE_SLIDESHOW;
        return $browser_types;
    }
}
