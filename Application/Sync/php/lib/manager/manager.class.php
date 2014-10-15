<?php
namespace application\ehb_sync;

use libraries\format\Theme;
use libraries\platform\Translation;
use libraries\architecture\Application;
use configuration\DataManager;

class Manager extends Application
{
    const APPLICATION_NAME = 'ehb_sync';
    const ACTION_BROWSE = 'browser';
    const ACTION_BAMAFLEX = 'bamaflex';
    const ACTION_ATLANTIS = 'atlantis';
    const ACTION_CAS = 'cas';
    const ACTION_DATA = 'data';
    const DEFAULT_ACTION = self :: ACTION_BROWSE;

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

    public static function get_installable_application_packages($include_installed = false)
    {
        $package_list = new \configuration\package\PackageList(
            self :: context(),
            Translation :: get('TypeName', null, __NAMESPACE__),
            Theme :: get_image_path() . 'logo/16.png');

        if (! DataManager :: get_registration(\application\ehb_sync\bamaflex\Manager :: context()) || $include_installed)
        {
            $package_list->add_package(\application\ehb_sync\bamaflex\Manager :: context());
        }

        if (! DataManager :: get_registration(\application\ehb_sync\atlantis\Manager :: context()) || $include_installed)
        {
            $package_list->add_package(\application\ehb_sync\atlantis\Manager :: context());
        }

        if (! DataManager :: get_registration(\application\ehb_sync\cas\Manager :: context()) || $include_installed)
        {
            $package_list->add_package(\application\ehb_sync\cas\Manager :: context());
        }

        $cas_list = new \configuration\package\PackageList(
            self :: context() . '\cas',
            Translation :: get('Cas', null, __NAMESPACE__));

        if (! DataManager :: get_registration(self :: context() . '\cas\data') || $include_installed)
        {
            $cas_list->add_package(self :: context() . '\cas\data');
        }

        if (! DataManager :: get_registration(self :: context() . '\cas\storage') || $include_installed)
        {
            $cas_list->add_package(self :: context() . '\cas\storage');
        }

        $package_list->add_child($cas_list);

        return $package_list;
    }
}
