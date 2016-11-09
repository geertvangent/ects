<?php
namespace Ehb\Application\Atlantis\UserGroup;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'context_action';
    const ACTION_BROWSE = 'Browser';
    const ACTION_VIEW = 'Viewer';
    const ACTION_DELETE = 'Deleter';
    const ACTION_EDIT = 'Editor';
    const ACTION_CREATE = 'Creator';
    const DEFAULT_ACTION = self::ACTION_BROWSE;
    const PARAM_CONTEXT_ID = 'context_id';
}
