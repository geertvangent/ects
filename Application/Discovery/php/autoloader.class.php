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
         'DiscoveryAjaxPlatformGroupsFeed' => '/ajax/platform_groups_feed.class.php',
         'DiscoveryAjaxUsersFeed' => '/ajax/users_feed.class.php',
         'DataConnector' => '/lib/data_connector.class.php',
         'DataSource' => '/lib/data_source.class.php',
         'DataManager' => '/lib/discovery_data_manager.class.php',
         'DataManagerInterface' => '/lib/discovery_data_manager_interface.class.php',
         'DiscoveryItem' => '/lib/discovery_item.class.php',
         'LegendTable' => '/lib/legend_table.class.php',
         'Module' => '/lib/module.class.php',
         'Parameters' => '/lib/parameters.class.php',
         'PlatformGroupEntity' => '/lib/platform_group_entity.class.php',
         'RightsGroupEntityRight' => '/lib/rights_group_entity_right.class.php',
         'SortableTable' => '/lib/sortable_table.class.php',
         'UserEntity' => '/lib/user_entity.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'DataSourceComponent' => '/lib/manager/component/data_source.class.php',
         'ModuleComponent' => '/lib/manager/component/module.class.php',
         'RightsComponent' => '/lib/manager/component/rights.class.php',
         'ViewerComponent' => '/lib/manager/component/viewer.class.php',
         'UserBrowserTable' => '/lib/manager/component/user_browser/user_browser_table.class.php',
         'UserBrowserTableCellRenderer' => '/lib/manager/component/user_browser/user_browser_table_cell_renderer.class.php',
         'UserBrowserTableColumnModel' => '/lib/manager/component/user_browser/user_browser_table_column_model.class.php',
         'UserBrowserTableDataProvider' => '/lib/manager/component/user_browser/user_browser_table_data_provider.class.php',
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