<?php
namespace application\atlantis;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Installer' => '/install/installer.class.php',
         'DataManager' => '/lib/data_manager.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'ApplicationComponent' => '/lib/manager/component/application.class.php',
         'RightComponent' => '/lib/manager/component/right.class.php',
         'RoleComponent' => '/lib/manager/component/role.class.php',
    );

    static function load($classname)
    {
        if (isset(self::$map[$classname]))
        {
            require_once __DIR__ . self::$map[$classname];
            return true;
        }

        return false;
   }

   static function synch($update){
        return \common\libraries\AutoloaderUtilities::synch(__DIR__, __DIR__, $update);
   }

}
?>