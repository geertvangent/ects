<?php
namespace application\discovery\module\student_year\implementation\bamaflex;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Module' => '/lib/module.class.php',
         'Rights' => '/lib/rights.class.php',
         'StudentYear' => '/lib/student_year.class.php',
         'DataSource' => '/lib/data_manager/data_source.class.php',
         'RightsPlatformGroupEntity' => '/lib/rights_entity/platform_group.class.php',
         'RightsUserEntity' => '/lib/rights_entity/user.class.php',
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