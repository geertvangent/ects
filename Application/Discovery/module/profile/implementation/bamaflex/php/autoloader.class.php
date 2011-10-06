<?php
namespace application\discovery\module\profile\implementation\bamaflex;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Address' => '/lib/address.class.php',
         'Birth' => '/lib/birth.class.php',
         'Module' => '/lib/module.class.php',
         'Nationality' => '/lib/nationality.class.php',
         'Profile' => '/lib/profile.class.php',
         'DataSource' => '/lib/data_manager/data_source.class.php',
         'SettingsConnector' => '/settings/settings_connector.class.php',
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