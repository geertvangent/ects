<?php
namespace Ehb\Application\Discovery\DataSource;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'action';
    const PARAM_TYPE = 'type';
    const PARAM_CONTENT_TYPE = 'content_type';
    const PARAM_DATA_SOURCE_ID = 'data_source_id';
    const ACTION_BROWSE_INSTANCES = 'browser';
    const ACTION_ACTIVATE_INSTANCE = 'activator';
    const ACTION_DEACTIVATE_INSTANCE = 'deactivator';
    const ACTION_UPDATE_INSTANCE = 'updater';
    const ACTION_DELETE_INSTANCE = 'deleter';
    const ACTION_CREATE_INSTANCE = 'creator';
    const ACTION_MOVE_INSTANCE = 'mover';
    const DEFAULT_ACTION = self :: ACTION_BROWSE_INSTANCES;
}
