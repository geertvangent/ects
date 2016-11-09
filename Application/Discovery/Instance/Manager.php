<?php
namespace Ehb\Application\Discovery\Instance;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'action';
    const PARAM_TYPE = 'type';
    const PARAM_CONTENT_TYPE = 'content_type';
    const PARAM_MODULE_ID = 'module_id';
    const ACTION_BROWSE_INSTANCES = 'Browser';
    const ACTION_ACTIVATE_INSTANCE = 'Activator';
    const ACTION_DEACTIVATE_INSTANCE = 'Deactivator';
    const ACTION_UPDATE_INSTANCE = 'Updater';
    const ACTION_DELETE_INSTANCE = 'Deleter';
    const ACTION_CREATE_INSTANCE = 'Creator';
    const ACTION_MOVE_INSTANCE = 'Mover';
    const DEFAULT_ACTION = self::ACTION_BROWSE_INSTANCES;
}
