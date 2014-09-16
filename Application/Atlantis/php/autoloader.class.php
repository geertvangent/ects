<?php
namespace application\atlantis;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var string[]
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'SessionBreadcrumbs' => '/lib/session_breadcrumbs.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'ApplicationComponent' => '/lib/manager/component/application.class.php',
         'ContextComponent' => '/lib/manager/component/context.class.php',
         'HomeComponent' => '/lib/manager/component/home.class.php',
         'RightComponent' => '/lib/manager/component/right.class.php',
         'RightsComponent' => '/lib/manager/component/rights.class.php',
         'RoleComponent' => '/lib/manager/component/role.class.php',
         'DataManager' => '/lib/storage/data_manager.class.php',
         'Activator' => '/package/activate/activator.class.php',
         'Deactivator' => '/package/deactivate/deactivator.class.php',
         'Installer' => '/package/install/installer.class.php',
         'SettingsConnector' => '/settings/connector.class.php'
    );

    /**
     * Try to load the class
     *
     * @param string $classname
     * @return boolean
     */
    public static function load($classname)
    {
        if (isset(self :: $map[$classname]))
        {
            require_once __DIR__ . self :: $map[$classname];
            return true;
        }

        return false;
    }

    /**
     * Synchronize the autoloader
     *
     * @param boolean $update
     * @return string[]
     */
    public static function synch($update)
    {
        return \libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }

}
?>