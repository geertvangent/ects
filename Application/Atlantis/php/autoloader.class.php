<?php
namespace application\atlantis;

class Autoloader
{

    /**
     * The array mapping class names to paths
     * 
     * @var multitype:string
     */
    private static $map = array(
        'Autoloader' => '/autoloader.class.php', 
        'DataManager' => '/lib/data_manager.class.php', 
        'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php', 
        'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php', 
        'Manager' => '/lib/manager/manager.class.php', 
        'ApplicationComponent' => '/lib/manager/component/application.class.php', 
        'ContextComponent' => '/lib/manager/component/context.class.php', 
        'HomeComponent' => '/lib/manager/component/home.class.php', 
        'RightComponent' => '/lib/manager/component/right.class.php', 
        'RoleComponent' => '/lib/manager/component/role.class.php', 
        'Activator' => '/package/activate/activator.class.php', 
        'Deactivator' => '/package/deactivate/deactivator.class.php', 
        'Installer' => '/package/install/installer.class.php', 
        'SettingsAtlantisConnector' => '/settings/settings_atlantis_connector.class.php');

    /**
     * Try to load the class
     * 
     * @param $classname string
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
     * @param $update boolean
     * @return multitype:string
     */
    public static function synch($update)
    {
        return \common\libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }
}
