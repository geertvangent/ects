<?php
namespace Ehb\Application\Atlantis\UserGroup;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'context_action';
    const ACTION_BROWSE = 'browser';
    const ACTION_VIEW = 'viewer';
    const ACTION_DELETE = 'deleter';
    const ACTION_EDIT = 'editor';
    const ACTION_CREATE = 'creator';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_CONTEXT_ID = 'context_id';
}
