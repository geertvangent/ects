<?php
namespace application\discovery\module\student_materials\implementation\bamaflex;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'BamaflexAjaxPlatformGroupsFeed' => '/ajax/platform_groups_feed.class.php',
         'BamaflexAjaxUsersFeed' => '/ajax/users_feed.class.php',
         'Material' => '/lib/material.class.php',
         'MaterialDescription' => '/lib/material_description.class.php',
         'MaterialStructured' => '/lib/material_structured.class.php',
         'Module' => '/lib/module.class.php',
         'Rights' => '/lib/rights.class.php',
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