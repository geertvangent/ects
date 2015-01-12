<?php
namespace Application\Discovery;

use libraries\platform\PlatformSetting;
use libraries\format\theme\Theme;
use libraries\architecture\application\Application;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class Manager extends Application
{
    const APPLICATION_NAME = 'discovery';
    const ACTION_VIEW = 'viewer';
    const ACTION_CODE = 'code';
    const ACTION_MODULE = 'module';
    const ACTION_DATA_SOURCE = 'data_source';
    const PARAM_USER_ID = 'user_id';
    const PARAM_MODULE_ID = 'module_id';
    const PARAM_OFFICIAL_CODE = 'official_code';
    const PARAM_CONTENT_TYPE = 'content_type';
    const PARAM_DIRECTION = 'direction';
    const PARAM_DIRECTION_UP = 'up';
    const PARAM_DIRECTION_DOWN = 'down';
    const PARAM_FORMAT = 'format';
    const PARAM_VIEW = 'view';
    const DEFAULT_ACTION = self :: ACTION_VIEW;

    /**
     * Constructor
     *
     * @param $user User The current user
     */
    public function __construct($user = null, $application = null)
    {
        parent :: __construct($user, $application);
        Theme :: set_theme(PlatformSetting :: get('theme', __NAMESPACE__));
    }

    public function get_application_name()
    {
        return self :: APPLICATION_NAME;
    }

    /**
     * Helper function for the Application class, pending access to class constants via variables in PHP 5.3 e.g. $name
     * = $class :: DEFAULT_ACTION DO NOT USE IN THIS APPLICATION'S CONTEXT Instead use: - self :: DEFAULT_ACTION in the
     * context of this class - YourApplicationManager :: DEFAULT_ACTION in all other application classes
     */
    public function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }
}
