<?php
namespace Ehb\Application\Atlantis\Application\Right;

use Chamilo\Libraries\Architecture\Application\Application;

/**
 * $Id: elude_manager.class.php
 *
 * @package application.elude
 */
abstract class Manager extends Application
{
    const PARAM_ACTION = 'right_action';
    const ACTION_BROWSE = 'Browser';
    const ACTION_VIEW = 'Viewer';
    const ACTION_DELETE = 'Deleter';
    const ACTION_EDIT = 'Editor';
    const ACTION_RIGHTS = 'Rights';
    const ACTION_CREATE = 'Creator';
    const ACTION_ADD_ROLE = 'AddRole';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;
    const PARAM_RIGHT_ID = 'right_id';
    const PARAM_APPLICATION_ID = 'application_id';
}
