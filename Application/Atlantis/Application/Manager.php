<?php
namespace Ehb\Application\Atlantis\Application;

abstract class Manager extends \Chamilo\Libraries\Architecture\Application\Application
{
    const PARAM_ACTION = 'application_action';
    const ACTION_BROWSE = 'Browser';
    const ACTION_DELETE = 'Deleter';
    const ACTION_EDIT = 'Editor';
    const ACTION_RIGHTS = 'Rights';
    const ACTION_CREATE = 'Creator';
    const ACTION_MANAGE_RIGHT = 'RightsManager';
    const ACTION_LIST = 'Lister';
    const ACTION_VIEW = 'Viewer';
    const DEFAULT_ACTION = self::ACTION_BROWSE;
    const PARAM_APPLICATION_ID = 'application_id';
    const PARAM_RIGHT_ID = 'right_id';
}
