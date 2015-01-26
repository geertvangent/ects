<?php
namespace Ehb\Application\Atlantis\Application;

abstract class Manager extends \Chamilo\Libraries\Architecture\Application\Application
{
    const PARAM_ACTION = 'application_action';
    const ACTION_BROWSE = 'browser';
    const ACTION_DELETE = 'deleter';
    const ACTION_EDIT = 'editor';
    const ACTION_RIGHTS = 'rights';
    const ACTION_CREATE = 'creator';
    const ACTION_MANAGE_RIGHT = 'rights_manager';
    const ACTION_LIST = 'lister';
    const ACTION_VIEW = 'viewer';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_APPLICATION_ID = 'application_id';
    const PARAM_RIGHT_ID = 'right_id';
}
