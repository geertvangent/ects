<?php
namespace application\atlantis\application;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Installer' => '/install/installer.class.php',
         'DataManager' => '/lib/data_manager.class.php',
         'Application' => '/lib/data_class/application.class.php',
         'ApplicationRight' => '/lib/data_class/application_right.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'EditorComponent' => '/lib/manager/component/editor.class.php',
         'RightsComponent' => '/lib/manager/component/rights.class.php',
         'ViewerComponent' => '/lib/manager/component/viewer.class.php',
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