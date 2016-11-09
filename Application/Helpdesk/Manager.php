<?php
namespace Ehb\Application\Helpdesk;

use Chamilo\Libraries\Architecture\Application\Application;

/**
 *
 * @package Ehb\Application\Helpdesk
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
abstract class Manager extends Application
{
    const ACTION_CREATE = 'Creator';
    const DEFAULT_ACTION = self::ACTION_CREATE;
}
