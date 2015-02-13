<?php
namespace Ehb\Application\Discovery;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
abstract class Manager extends Application
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
    public function __construct(\Symfony\Component\HttpFoundation\Request $request, $user = null, $application = null)
    {
        parent :: __construct($request, $user, $application);
        Theme :: getInstance()->setTheme(PlatformSetting :: get('theme', __NAMESPACE__));
    }

    public function get_application_name()
    {
        return self :: APPLICATION_NAME;
    }
}
