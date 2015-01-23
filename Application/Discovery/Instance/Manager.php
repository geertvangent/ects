<?php
namespace Chamilo\Application\Discovery\Instance;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'action';
    const PARAM_TYPE = 'type';
    const PARAM_CONTENT_TYPE = 'content_type';
    const PARAM_MODULE_ID = 'module_id';
    const ACTION_BROWSE_INSTANCES = 'browser';
    const ACTION_ACTIVATE_INSTANCE = 'activator';
    const ACTION_DEACTIVATE_INSTANCE = 'deactivator';
    const ACTION_UPDATE_INSTANCE = 'updater';
    const ACTION_DELETE_INSTANCE = 'deleter';
    const ACTION_CREATE_INSTANCE = 'creator';
    const ACTION_MOVE_INSTANCE = 'mover';
    const DEFAULT_ACTION = self :: ACTION_BROWSE_INSTANCES;

    public static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }
}
