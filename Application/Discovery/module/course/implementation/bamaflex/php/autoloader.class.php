<?php
namespace application\discovery\module\course\implementation\bamaflex;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var string[]
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Activity' => '/lib/activity.class.php',
         'ActivityDescription' => '/lib/activity_description.class.php',
         'ActivityStructured' => '/lib/activity_structured.class.php',
         'ActivityTotal' => '/lib/activity_total.class.php',
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
         'Rights' => '/lib/rights.class.php',
         'SecondChance' => '/lib/second_chance.class.php',
         'Teacher' => '/lib/teacher.class.php',
         'TimeframePart' => '/lib/timeframe_part.class.php',
         'DataSource' => '/lib/data_manager/data_source.class.php',
         'RenditionImplementation' => '/lib/rendition/rendition.class.php',
         'HtmlDefaultRenditionImplementation' => '/lib/rendition/html/default.class.php',
         'HtmlXlsxRenditionImplementation' => '/lib/rendition/html/xlsx.class.php',
         'XlsxDefaultRenditionImplementation' => '/lib/rendition/xlsx/default.class.php',
         'SettingsConnector' => '/settings/settings_connector.class.php'
    );

    /**
     * Try to load the class
     *
     * @param string $classname
     * @return boolean
     */
    public static function load($classname)
    {
        if (isset(self :: $map[$classname]))
        {
            require_once __DIR__ . self :: $map[$classname];
            return true;
        }

        return false;
    }

    /**
     * Synchronize the autoloader
     *
     * @param boolean $update
     * @return string[]
     */
    public static function synch($update)
    {
        return \libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }

}