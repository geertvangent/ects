<?php
namespace application\ehb_sync\cas\data;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var multitype:string
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'DoctrineConnection' => '/lib/storage/data_manager/connection/doctrine.class.php',
         'Mdb2Connection' => '/lib/storage/data_manager/connection/mdb2.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'Statistic' => '/lib/storage/data_class/statistic.class.php',
         'DataManager' => '/lib/storage/data_manager/data_manager.class.php',
         'DataManagerInterface' => '/lib/storage/data_manager/data_manager_interface.class.php',
         'DoctrineDataManager' => '/lib/storage/data_manager/implementation/doctrine.class.php',
         'Mdb2DataManager' => '/lib/storage/data_manager/implementation/mdb2.class.php',
         'Activator' => '/package/activate/activator.class.php',
         'Deactivator' => '/package/deactivate/deactivator.class.php',
         'Installer' => '/package/install/installer.class.php'
    );

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
?>