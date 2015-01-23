<?php
namespace Ehb\Application\Atlantis\UserGroup\Ajax;

use Chamilo\Libraries\Architecture\AjaxManager;
use Chamilo\Libraries\Architecture\JsonAjaxResult;

/**
 *
 * @package Ehb\Application\Atlantis\UserGroup\Ajax
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
abstract class Manager extends AjaxManager
{

    public static function get_default_action()
    {
        JsonAjaxResult :: bad_request();
    }
}
