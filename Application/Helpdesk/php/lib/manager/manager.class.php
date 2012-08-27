<?php
namespace application\ehb_helpdesk;

use common\libraries\Theme;
use common\libraries\Translation;
use core\lynx\PackageList;
use common\libraries\CommonDataManager;
use common\libraries\WebApplication;

class Manager extends WebApplication
{
    const APPLICATION_NAME = 'rt';
    const ACTION_CREATE = 'creator';
    const DEFAULT_ACTION = self :: ACTION_CREATE;

    /**
     * Constructor
     *
     * @param $user_id int
     */
    public function __construct($user)
    {
        parent :: __construct($user);
    }

    /**
     * Helper function for the Application class, pending access to class constants via variables in PHP 5.3 e.g. $name
     * = $class :: APPLICATION_NAME DO NOT USE IN THIS APPLICATION'S CONTEXT Instead use: - self :: APPLICATION_NAME in
     * the context of this class - YourApplicationManager :: APPLICATION_NAME in all other application classes
     */
    function get_application_name()
    {
        return self :: APPLICATION_NAME;
    }

    /**
     * Helper function for the Application class, pending access to class constants via variables in PHP 5.3 e.g. $name
     * = $class :: DEFAULT_ACTION DO NOT USE IN THIS APPLICATION'S CONTEXT Instead use: - self :: DEFAULT_ACTION in the
     * context of this class - YourApplicationManager :: DEFAULT_ACTION in all other application classes
     */
    function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }

    static function get_installable_application_packages($include_installed = false)
    {
        $package_list = new PackageList(self :: context(), Translation :: get('TypeName', null, __NAMESPACE__),
                Theme :: get_image_path() . 'logo/16.png');
        if (! CommonDataManager :: get_registration(\application\ehb_sync\bamaflex\Manager :: context()) || $include_installed)
        {
            $package_list->add_package(\application\ehb_sync\bamaflex\Manager :: context());
        }
        return $package_list;
    }
}
?>