<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Note;

use Chamilo\Core\Repository\ContentObject\Note\Storage\DataClass\Note;

/**
 * $Id: note_tool.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.note
 */

/**
 * This tool allows a user to publish notes in his or her course.
 */
abstract class Manager extends \Ehb\Application\Avilarts\Tool\Manager
{
    const ACTION_VIEW_NOTES = 'Viewer';

    public static function get_allowed_types()
    {
        return array(Note :: CLASS_NAME);
    }
}
