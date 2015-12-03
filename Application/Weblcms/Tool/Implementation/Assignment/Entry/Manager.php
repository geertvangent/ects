<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry;

use Chamilo\Libraries\Architecture\Application\Application;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Assignment\Entry
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
abstract class Manager extends Application
{
    // Actions
    const ACTION_CREATE = 'Creator';
    const ACTION_ENTITIES = 'Entities';

    // Parameters
    const PARAM_ACTION = 'entry_action';
}
