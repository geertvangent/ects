<?php
namespace application\discovery\module\course\implementation\bamaflex;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Activity' => '/lib/activity.class.php',
         'ActivityDescription' => '/lib/activity_description.class.php',
         'ActivityStructured' => '/lib/activity_structured.class.php',
         'Competence' => '/lib/competence.class.php',
         'CompetenceDescription' => '/lib/competence_description.class.php',
         'CompetenceStructured' => '/lib/competence_structured.class.php',
         'Cost' => '/lib/cost.class.php',
         'Course' => '/lib/course.class.php',
         'Evaluation' => '/lib/evaluation.class.php',
         'EvaluationDescription' => '/lib/evaluation_description.class.php',
         'EvaluationStructured' => '/lib/evaluation_structured.class.php',
         'FollowingImpossible' => '/lib/following_impossible.class.php',
         'Language' => '/lib/language.class.php',
         'Material' => '/lib/material.class.php',
         'MaterialDescription' => '/lib/material_description.class.php',
         'MaterialStructured' => '/lib/material_structured.class.php',
         'Module' => '/lib/module.class.php',
         'Parameters' => '/lib/parameters.class.php',
         'SecondChance' => '/lib/second_chance.class.php',
         'Teacher' => '/lib/teacher.class.php',
         'TimeframePart' => '/lib/timeframe_part.class.php',
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