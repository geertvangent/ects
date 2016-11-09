<?php
namespace Ehb\Application\Atlantis\Context;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'context_action';
    const ACTION_BROWSE = 'Browser';
    const ACTION_DELETE = 'Delete';
    const DEFAULT_ACTION = self::ACTION_BROWSE;
    const PARAM_CONTEXT_ID = 'context_id';
}