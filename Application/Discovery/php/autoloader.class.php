<?php
namespace application\discovery;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var multitype:string
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'DataConnector' => '/lib/data_connector.class.php',
         'DataManager' => '/lib/data_manager.class.php',
         'DataManagerInterface' => '/lib/data_manager_interface.class.php',
         'DataSource' => '/lib/data_source.class.php',
         'DiscoveryItem' => '/lib/discovery_item.class.php',
         'LegendTable' => '/lib/legend_table.class.php',
         'Module' => '/lib/module.class.php',
         'Parameters' => '/lib/parameters.class.php',
         'RightsGroupEntityRight' => '/lib/rights_group_entity_right.class.php',
         'SortableTable' => '/lib/sortable_table.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'DataSourceComponent' => '/lib/manager/component/data_source.class.php',
         'ModuleComponent' => '/lib/manager/component/module.class.php',
         'ViewerComponent' => '/lib/manager/component/viewer.class.php',
         'CodeComponent' => '/lib/manager/component/code.class.php',
         'AbstractRenditionImplementation' => '/lib/rendition/abstract_rendition_implementation.class.php',
         'DummyRenditionImplementation' => '/lib/rendition/dummy_rendition_implementation.class.php',
         'Rendition' => '/lib/rendition/rendition.class.php',
         'RenditionImplementation' => '/lib/rendition/rendition_implementation.class.php',
         'HtmlRendition' => '/lib/rendition/format/html.class.php',
         'XlsxRendition' => '/lib/rendition/format/xlsx.class.php',
         'ZipRendition' => '/lib/rendition/format/zip.class.php',
         'HtmlDefaultRendition' => '/lib/rendition/view/html/default.class.php',
         'HtmlXlsxRendition' => '/lib/rendition/view/html/xlsx.class.php',
         'HtmlZipRendition' => '/lib/rendition/view/html/zip.class.php',
         'XlsxDefaultRendition' => '/lib/rendition/view/xlsx/default.class.php',
         'ZipDefaultRendition' => '/lib/rendition/view/zip/default.class.php',
         'FacultyBasedRights' => '/lib/rights/faculty_based.class.php',
         'TrainingBasedRights' => '/lib/rights/training_based.class.php',
         'TrainingBasedContext' => '/lib/rights/training_based_context.class.php',
         'UserBasedRights' => '/lib/rights/user_based.class.php',
         'Activator' => '/package/activate/activator.class.php',
         'Deactivator' => '/package/deactivate/deactivator.class.php',
         'Installer' => '/package/install/installer.class.php',
         'SettingsConnector' => '/settings/connector.class.php'
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
        return \common\libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }

}
?>