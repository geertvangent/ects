<?php
namespace Ehb\Application\Helpdesk;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const ACTION_CREATE = 'Creator';
    const DEFAULT_ACTION = self :: ACTION_CREATE;
}
