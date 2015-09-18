<?php
namespace Ehb\Application\Discovery;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\Architecture\Application\ApplicationConfigurationInterface;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
abstract class Manager extends Application
{
    const APPLICATION_NAME = 'discovery';
    const ACTION_VIEW = 'Viewer';
    const ACTION_CODE = 'Code';
    const ACTION_MODULE = 'Module';
    const ACTION_DATA_SOURCE = 'DataSource';
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
    public function __construct(ApplicationConfigurationInterface $applicationConfiguration)
    {
        parent :: __construct($applicationConfiguration);
        Theme :: getInstance()->setTheme(PlatformSetting :: get('theme', __NAMESPACE__));
    }

    public function get_application_name()
    {
        return self :: APPLICATION_NAME;
    }
}
