<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Choice' => '/lib/choice.class.php',
         'ChoiceOption' => '/lib/choice_option.class.php',
         'Language' => '/lib/language.class.php',
         'Major' => '/lib/major.class.php',
         'MajorChoice' => '/lib/major_choice.class.php',
         'MajorChoiceOption' => '/lib/major_choice_option.class.php',
         'Module' => '/lib/module.class.php',
         'Package' => '/lib/package.class.php',
         'PackageCourse' => '/lib/package_course.class.php',
         'Parameters' => '/lib/parameters.class.php',
         'SubTrajectory' => '/lib/sub_trajectory.class.php',
         'SubTrajectoryCourse' => '/lib/sub_trajectory_course.class.php',
         'Trajectory' => '/lib/trajectory.class.php',
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