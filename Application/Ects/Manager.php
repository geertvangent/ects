<?php
namespace Ehb\Application\Ects;

use Chamilo\Libraries\Architecture\Application\Application;

/**
 *
 * @package Ehb\Application\Ects
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
abstract class Manager extends Application
{
    // Actions
    const ACTION_VIEW = 'Viewer';

    // Default action
    const DEFAULT_ACTION = self::ACTION_VIEW;
}
