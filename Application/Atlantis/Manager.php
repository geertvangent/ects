<?php
namespace Ehb\Application\Atlantis;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;

abstract class Manager extends Application
{
    const APPLICATION_NAME = 'atlantis';
    const ACTION_CONTEXT = 'context';
    const ACTION_ROLE = 'role';
    const ACTION_ENTITLEMENT = 'entitlement';
    const ACTION_ENTITY = 'entity';
    const ACTION_APPLICATION = 'application';
    const ACTION_HOME = 'home';
    const ACTION_RIGHTS = 'rights';
    const DEFAULT_ACTION = self :: ACTION_HOME;

    public function __construct(\Symfony\Component\HttpFoundation\Request $request, $user = null, $application = null)
    {
        parent :: __construct($request, $user, $application);

        if (! \Ehb\Application\Atlantis\Rights\Rights :: get_instance()->access_is_allowed())
        {
            throw new NotAllowedException();
        }

        Theme :: getInstance()->setTheme(PlatformSetting :: get('theme', __NAMESPACE__));
    }

    /**
     * Helper function for the Application class, pending access to class constants via variables in PHP 5.3 e.g.
     * $name
     * = $class :: APPLICATION_NAME DO NOT USE IN THIS APPLICATION'S CONTEXT Instead use: - self :: APPLICATION_NAME in
     * the context of this class - YourApplicationManager :: APPLICATION_NAME in all other application classes
     */
    public function get_application_name()
    {
        return self :: APPLICATION_NAME;
    }

    function display_header()
    {
        BreadcrumbTrail :: get_instance()->set(array_values(SessionBreadcrumbs :: get()));
        parent :: display_header();
    }
}
