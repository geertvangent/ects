<?php
namespace Ehb\Application\Atlantis\Context\Ajax;

use Chamilo\Libraries\Architecture\AjaxManager;
use Chamilo\Libraries\Architecture\JsonAjaxResult;

/**
 *
 * @package Ehb\Application\Atlantis\Context
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class Manager extends AjaxManager
{

    public static function get_default_action()
    {
        JsonAjaxResult :: bad_request();
    }
}
