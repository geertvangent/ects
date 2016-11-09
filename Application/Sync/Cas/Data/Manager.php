<?php
namespace Ehb\Application\Sync\Cas\Data;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'account_action';
    const ACTION_BROWSE = 'Browser';
    const DEFAULT_ACTION = self::ACTION_BROWSE;
}
