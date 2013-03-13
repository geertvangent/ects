<?php
namespace application\ehb_sync;

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
        'DataManagerInterface' => '/lib/data_manager_interface.class.php', 
        'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php', 
        'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php', 
        'Manager' => '/lib/manager/manager.class.php', 
        'AtlantisComponent' => '/lib/manager/component/atlantis.class.php', 
        'BamaflexComponent' => '/lib/manager/component/bamaflex.class.php', 
        'BrowserComponent' => '/lib/manager/component/browser.class.php', 
        'CasComponent' => '/lib/manager/component/cas.class.php', 
        'Activator' => '/package/activate/activator.class.php', 
        'Deactivator' => '/package/deactivate/deactivator.class.php', 
        'Installer' => '/package/install/installer.class.php');

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
