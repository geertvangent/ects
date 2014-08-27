<?php
namespace application\discovery\module\elo\implementation\chamilo;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var multitype:string
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Module' => '/lib/module.class.php',
         'Rights' => '/lib/rights.class.php',
         'TypeData' => '/lib/type.class.php',
         'TypeDataFilter' => '/lib/type_filter.class.php',
         'DataManager' => '/lib/data_manager/data_manager.class.php',
         'DataManagerInterface' => '/lib/data_manager/data_manager_interface.class.php',
         'DataSource' => '/lib/data_manager/data_source.class.php',
         'DoctrineDataManager' => '/lib/data_manager/implementation/doctrine.class.php',
         'ContentObjectDataFilter' => '/lib/filter/content_object.class.php',
         'CourseAccessDataFilter' => '/lib/filter/course_access.class.php',
         'CourseListDataFilter' => '/lib/filter/course_list.class.php',
         'CourseTimeDataFilter' => '/lib/filter/course_time.class.php',
         'DocumentListDataFilter' => '/lib/filter/document_list.class.php',
         'LoginDataFilter' => '/lib/filter/login.class.php',
         'PublicationDataFilter' => '/lib/filter/publication.class.php',
         'FilterForm' => '/lib/form/filter.class.php',
         'RenditionImplementation' => '/lib/rendition/rendition.class.php',
         'HtmlDefaultRenditionImplementation' => '/lib/rendition/html/default.class.php',
         'HtmlXlsxRenditionImplementation' => '/lib/rendition/html/xlsx.class.php',
         'XlsxDefaultRenditionImplementation' => '/lib/rendition/xlsx/default.class.php',
         'ContentObjectData' => '/lib/type/content_object.class.php',
         'CourseAccessData' => '/lib/type/course_access.class.php',
         'CourseListData' => '/lib/type/course_list.class.php',
         'CourseTimeData' => '/lib/type/course_time.class.php',
         'DocumentListData' => '/lib/type/document_list.class.php',
         'LoginData' => '/lib/type/login.class.php',
         'PublicationData' => '/lib/type/publication.class.php',
         'application\discovery\module\profile\implementation\chamilo\SettingsConnector' => '/settings/settings_connector.class.php'
    );

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