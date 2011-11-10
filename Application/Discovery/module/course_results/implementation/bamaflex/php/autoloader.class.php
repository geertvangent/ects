<?php
namespace application\discovery\module\course_results\implementation\bamaflex;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'application\discovery\module\profile\implementation\bamaflex\BamaflexAjaxPlatformGroupsFeed' => '/ajax/platform_groups_feed.class.php',
         'application\discovery\module\profile\implementation\bamaflex\BamaflexAjaxUsersFeed' => '/ajax/users_feed.class.php',
         'CourseResult' => '/lib/course_result.class.php',
         'Mark' => '/lib/mark.class.php',
         'Module' => '/lib/module.class.php',
         'Parameters' => '/lib/parameters.class.php',
         'application\discovery\module\profile\implementation\bamaflex\Rights' => '/lib/rights.class.php',
         'DataSource' => '/lib/data_manager/data_source.class.php',
         'application\discovery\module\profile\implementation\bamaflex\RightsPlatformGroupEntity' => '/lib/rights_entity/platform_group.class.php',
         'application\discovery\module\profile\implementation\bamaflex\RightsUserEntity' => '/lib/rights_entity/user.class.php',
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