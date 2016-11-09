<?php
namespace Ehb\Application\Atlantis;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Application\ApplicationConfigurationInterface;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;

abstract class Manager extends Application
{
    const ACTION_CONTEXT = 'Context';
    const ACTION_ROLE = 'Role';
    const ACTION_ENTITLEMENT = 'Entitlement';
    const ACTION_ENTITY = 'Entity';
    const ACTION_APPLICATION = 'Application';
    const ACTION_HOME = 'Home';
    const ACTION_RIGHTS = 'Rights';
    const DEFAULT_ACTION = self :: ACTION_HOME;

    public function __construct(ApplicationConfigurationInterface $applicationConfiguration)
    {
        parent :: __construct($applicationConfiguration);

        if (! \Ehb\Application\Atlantis\Rights :: getInstance()->access_is_allowed())
        {
            throw new NotAllowedException();
        }

        Theme :: getInstance()->setTheme(PlatformSetting :: get('theme', __NAMESPACE__));
    }

    function render_header()
    {
        BreadcrumbTrail :: getInstance()->set(array_values(SessionBreadcrumbs :: get()));
        return parent :: render_header();
    }
}
