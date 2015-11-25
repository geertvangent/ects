<?php
namespace Ehb\Application\Atlantis\Role\Entitlement;

use Chamilo\Libraries\Architecture\Application\Application;

abstract class Manager extends Application
{
    const PARAM_ACTION = 'entitlement_action';
    const ACTION_BROWSE = 'Browser';
    const ACTION_VIEW = 'Viewer';
    const ACTION_DELETE = 'Deleter';
    const ACTION_EDIT = 'Editor';
    const ACTION_RIGHTS = 'Rights';
    const ACTION_LIST = 'Lister';
    const PARAM_ENTITLEMENT_ID = 'entitlement_id';
    const DEFAULT_ACTION = self :: ACTION_LIST;
}
