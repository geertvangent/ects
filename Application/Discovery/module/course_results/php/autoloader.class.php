<?php
namespace application\discovery\module\course_results;

class Autoloader
{

    /**
     * The array mapping class names to paths
     * 
     * @var multitype:string
     */
    private static $map = array(
        'Autoloader' => '/autoloader.class.php', 
        'CourseResult' => '/lib/course_result.class.php', 
        'DataManager' => '/lib/data_manager.class.php', 
        'DataManagerInterface' => '/lib/data_manager_interface.class.php', 
        'Mark' => '/lib/mark.class.php', 
        'Module' => '/lib/module.class.php', 
        'Parameters' => '/lib/parameters.class.php', 
        'Rendition' => '/lib/rendition/rendition.class.php', 
        'HtmlRendition' => '/lib/rendition/format/html.class.php', 
        'XlsxRendition' => '/lib/rendition/format/xlsx.class.php', 
        'HtmlDefaultRendition' => '/lib/rendition/view/html/default.class.php', 
        'XlsxDefaultRendition' => '/lib/rendition/view/xlsx/default.class.php');

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