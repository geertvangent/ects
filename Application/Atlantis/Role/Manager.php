<?php
namespace Ehb\Application\Atlantis\Role;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'action';
    const ACTION_BROWSE = 'Browser';
    const ACTION_VIEW = 'Viewer';
    const ACTION_DELETE = 'Deleter';
    const ACTION_EDIT = 'Editor';
    const ACTION_RIGHTS = 'Rights';
    const ACTION_CREATE = 'Creator';
    const ACTION_ENTITLEMENT = 'Entitlement';
    const ACTION_ENTITY = 'Entity';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_ROLE_ID = 'role_id';
}