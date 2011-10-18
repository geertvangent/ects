<?php
namespace application\discovery\module\student_materials\implementation\bamaflex;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Course' => '/lib/course.class.php',
         'Material' => '/lib/material.class.php',
         'MaterialDescription' => '/lib/material_description.class.php',
         'MaterialStructured' => '/lib/material_structured.class.php',
         'Module' => '/lib/module.class.php',
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