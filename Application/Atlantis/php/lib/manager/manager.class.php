<?php
namespace application\atlantis;

use common\libraries\Theme;

use common\libraries\CommonDataManager;

use common\libraries\Translation;

use admin\AdminDataManager;

use core\lynx\PackageList;

use common\libraries\WebApplication;

class Manager extends WebApplication
{
    const APPLICATION_NAME = 'atlantis';

    const ACTION_CONTEXT = 'context';
    const ACTION_ROLE = 'role';
    const ACTION_ENTITLEMENT = 'entitlement';
    const ACTION_ENTITY = 'entity';
    const ACTION_APPLICATION = 'application';
    const ACTION_HOME = 'home';

    const DEFAULT_ACTION = self :: ACTION_HOME;

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
        $package_list = new PackageList(self :: context(), Translation :: get('TypeName', null, __NAMESPACE__), Theme :: get_image_path() . 'logo/16.png');

        if (! CommonDataManager :: get_registration(\application\atlantis\application\Manager :: context()) || $include_installed)
        {
            $package_list->add_package(\application\atlantis\application\Manager :: context());
        }

        if (! CommonDataManager :: get_registration(\application\atlantis\context\Manager :: context()) || $include_installed)
        {
            $package_list->add_package(\application\atlantis\context\Manager :: context());
        }

        if (! CommonDataManager :: get_registration(\application\atlantis\role\Manager :: context()) || $include_installed)
        {
            $package_list->add_package(\application\atlantis\role\Manager :: context());
        }

        if (! CommonDataManager :: get_registration(\application\atlantis\user_group\Manager :: context()) || $include_installed)
        {
            $package_list->add_package(\application\atlantis\user_group\Manager :: context());
        }

        $application_list = new PackageList(\application\atlantis\application\Manager :: context(), Translation :: get('TypeName', null, \application\atlantis\application\Manager :: context()), Theme :: get_image_path(\application\atlantis\application\Manager :: context()) . 'logo/16.png');

        if (! CommonDataManager :: get_registration(\application\atlantis\application\right\Manager :: context()) || $include_installed)
        {
            $application_list->add_package(\application\atlantis\application\right\Manager :: context());
        }

        $package_list->add_child($application_list);

        $role_list = new PackageList(\application\atlantis\role\Manager :: context(), Translation :: get('TypeName', null, \application\atlantis\role\Manager :: context()), Theme :: get_image_path(\application\atlantis\role\Manager :: context()) . 'logo/16.png');

        if (! CommonDataManager :: get_registration(\application\atlantis\role\entitlement\Manager :: context()) || $include_installed)
        {
            $role_list->add_package(\application\atlantis\role\entitlement\Manager :: context());
        }

        if (! CommonDataManager :: get_registration(\application\atlantis\role\entity\Manager :: context()) || $include_installed)
        {
            $role_list->add_package(\application\atlantis\role\entity\Manager :: context());
        }

        $package_list->add_child($role_list);

        return $package_list;
    }
}
?>