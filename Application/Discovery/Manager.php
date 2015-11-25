<?php
namespace Ehb\Application\Discovery;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\Architecture\Application\ApplicationConfigurationInterface;

/**
 *
 * @package Ehb\Application\Discovery
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
abstract class Manager extends Application
{
    // Actions
    const ACTION_VIEW = 'Viewer';
    const ACTION_CODE = 'Code';
    const ACTION_MODULE = 'Module';
    const ACTION_DATA_SOURCE = 'DataSource';
    const ACTION_PHOTO = 'Photo';

    // Parameters
    const PARAM_USER_ID = 'user_id';
    const PARAM_MODULE_ID = 'module_id';
    const PARAM_OFFICIAL_CODE = 'official_code';
    const PARAM_CONTENT_TYPE = 'content_type';
    const PARAM_DIRECTION = 'direction';
    const PARAM_DIRECTION_UP = 'up';
    const PARAM_DIRECTION_DOWN = 'down';
    const PARAM_FORMAT = 'format';
    const PARAM_VIEW = 'view';

    // Default action
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
}
