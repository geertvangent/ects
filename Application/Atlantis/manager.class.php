<?php
namespace Chamilo\Application\Atlantis;

use libraries\format\BreadcrumbTrail;
use libraries\format\theme\Theme;
use libraries\architecture\application\Application;
use libraries\architecture\NotAllowedException;
use libraries\platform\PlatformSetting;

class Manager extends Application
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

    public function __construct($user = null, $application = null)
    {
        parent :: __construct($user, $application);

        if (! \application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
        {
            throw new NotAllowedException();
        }

        Theme :: set_theme(PlatformSetting :: get('theme', __NAMESPACE__));
    }

    /**
     * Helper function for the Application class, pending access to class constants via variables in PHP 5.3 e.g. $name
     * = $class :: APPLICATION_NAME DO NOT USE IN THIS APPLICATION'S CONTEXT Instead use: - self :: APPLICATION_NAME in
     * the context of this class - YourApplicationManager :: APPLICATION_NAME in all other application classes
     */
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

    function display_header()
    {
        BreadcrumbTrail :: get_instance()->set(array_values(SessionBreadcrumbs :: get()));
        parent :: display_header();
    }
}
