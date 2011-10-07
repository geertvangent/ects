<?php
namespace application\discovery\module\course_results;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'CourseResult' => '/lib/course_result.class.php',
         'DataManager' => '/lib/data_manager.class.php',
         'DataManagerInterface' => '/lib/data_manager_interface.class.php',
         'Mark' => '/lib/mark.class.php',
         'MarkMoment' => '/lib/mark_moment.class.php',
         'Module' => '/lib/module.class.php',
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