<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

class Autoloader
{

    /**
     * The array mapping class names to paths
     * 
     * @var multitype:string
     */
    private static $map = array(
        'Autoloader' => '/autoloader.class.php', 
        'Choice' => '/lib/choice.class.php', 
        'ChoiceOption' => '/lib/choice_option.class.php', 
        'Group' => '/lib/group.class.php', 
        'Language' => '/lib/language.class.php', 
        'Major' => '/lib/major.class.php', 
        'MajorChoice' => '/lib/major_choice.class.php', 
        'MajorChoiceOption' => '/lib/major_choice_option.class.php', 
        'Module' => '/lib/module.class.php', 
        'Package' => '/lib/package.class.php', 
        'PackageCourse' => '/lib/package_course.class.php', 
        'Parameters' => '/lib/parameters.class.php', 
        'Rights' => '/lib/rights.class.php', 
        'SubTrajectory' => '/lib/sub_trajectory.class.php', 
        'SubTrajectoryCourse' => '/lib/sub_trajectory_course.class.php', 
        'Trajectory' => '/lib/trajectory.class.php', 
        'DataSource' => '/lib/data_manager/data_source.class.php', 
        'RenditionImplementation' => '/lib/rendition/rendition.class.php', 
        'HtmlDefaultRenditionImplementation' => '/lib/rendition/html/default.class.php', 
        'HtmlXlsxRenditionImplementation' => '/lib/rendition/html/xlsx.class.php', 
        'XlsxDefaultRenditionImplementation' => '/lib/rendition/xlsx/default.class.php', 
        'SettingsConnector' => '/settings/settings_connector.class.php');

    /**
     * Try to load the class
     * 
     * @param $classname string
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
     * @param $update boolean
     * @return multitype:string
     */
    public static function synch($update)
    {
        return \libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }
}
?>