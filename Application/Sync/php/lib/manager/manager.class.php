<?php
namespace application\ehb_sync;

use common\libraries\Theme;
use common\libraries\Translation;
use core\lynx\PackageList;
use common\libraries\CommonDataManager;
use common\libraries\WebApplication;

class Manager extends WebApplication
{
    const APPLICATION_NAME = 'ehb_sync';
    const ACTION_BROWSE = 'browser';
    const ACTION_BAMAFLEX = 'bamaflex';
    const ACTION_ATLANTIS = 'atlantis';
    const ACTION_CAS = 'cas';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;

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
        if (! CommonDataManager :: get_registration(\application\ehb_sync\atlantis\Manager :: context()) || $include_installed)
        {
            $package_list->add_package(\application\ehb_sync\atlantis\Manager :: context());
        }
        if (! CommonDataManager :: get_registration(\application\ehb_sync\cas\Manager :: context()) || $include_installed)
        {
            $package_list->add_package(\application\ehb_sync\cas\Manager :: context());
        }

        $cas_list = new PackageList(\application\ehb_sync\cas\Manager :: context(),
                Translation :: get('TypeName', null, \application\ehb_sync\cas\Manager :: context()),
                Theme :: get_image_path(\application\ehb_sync\cas\Manager :: context()) . 'logo/16.png');

        if (! CommonDataManager :: get_registration(\application\ehb_sync\cas\storage\Manager :: context()) || $include_installed)
        {
            $cas_list->add_package(\application\ehb_sync\cas\storage\Manager :: context());
        }

        if (! CommonDataManager :: get_registration(\application\ehb_sync\cas\data\Manager :: context()) || $include_installed)
        {
            $cas_list->add_package(\application\ehb_sync\cas\data\Manager :: context());
        }

        $package_list->add_child($cas_list);

        return $package_list;
    }
}
?>