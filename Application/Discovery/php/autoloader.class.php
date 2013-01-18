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
         'DataSourceInstance' => '/lib/data_source_instance.class.php',
         'DataSourceInstanceSetting' => '/lib/data_source_instance_setting.class.php',
         'DiscoveryDataManager' => '/lib/discovery_data_manager.class.php',
         'DiscoveryDataManagerInterface' => '/lib/discovery_data_manager_interface.class.php',
         'DiscoveryItem' => '/lib/discovery_item.class.php',
         'LegendTable' => '/lib/legend_table.class.php',
         'Module' => '/lib/module.class.php',
         'ModuleInstance' => '/lib/module_instance.class.php',
         'ModuleInstanceSetting' => '/lib/module_instance_setting.class.php',
         'Parameters' => '/lib/parameters.class.php',
         'PlatformGroupEntity' => '/lib/platform_group_entity.class.php',
         'RightsGroupEntityRight' => '/lib/rights_group_entity_right.class.php',
         'SortableTable' => '/lib/sortable_table.class.php',
         'UserEntity' => '/lib/user_entity.class.php',
         'Mdb2DiscoveryDataManager' => '/lib/data_manager/mdb2.class.php',
         'DiscoveryManager' => '/lib/discovery_manager/discovery_manager.class.php',
         'DiscoveryManagerBrowserComponent' => '/lib/discovery_manager/component/browser.class.php',
         'DiscoveryManagerModuleComponent' => '/lib/discovery_manager/component/module.class.php',
         'DiscoveryManagerRightsComponent' => '/lib/discovery_manager/component/rights.class.php',
         'DiscoveryManagerViewerComponent' => '/lib/discovery_manager/component/viewer.class.php',
         'UserBrowserTable' => '/lib/discovery_manager/component/user_browser/user_browser_table.class.php',
         'UserBrowserTableCellRenderer' => '/lib/discovery_manager/component/user_browser/user_browser_table_cell_renderer.class.php',
         'UserBrowserTableColumnModel' => '/lib/discovery_manager/component/user_browser/user_browser_table_column_model.class.php',
         'UserBrowserTableDataProvider' => '/lib/discovery_manager/component/user_browser/user_browser_table_data_provider.class.php',
         'ModuleInstanceManager' => '/lib/module_instance_manager/module_instance_manager.class.php',
         'ModuleInstanceManagerActivatorComponent' => '/lib/module_instance_manager/component/activator.class.php',
         'ModuleInstanceManagerBrowserComponent' => '/lib/module_instance_manager/component/browser.class.php',
         'ModuleInstanceManagerCreatorComponent' => '/lib/module_instance_manager/component/creator.class.php',
         'ModuleInstanceManagerDeactivatorComponent' => '/lib/module_instance_manager/component/deactivator.class.php',
         'ModuleInstanceManagerDeleterComponent' => '/lib/module_instance_manager/component/deleter.class.php',
         'ModuleInstanceManagerMoverComponent' => '/lib/module_instance_manager/component/mover.class.php',
         'ModuleInstanceManagerRightsEditorComponent' => '/lib/module_instance_manager/component/rights_editor.class.php',
         'ModuleInstanceManagerUpdaterComponent' => '/lib/module_instance_manager/component/updater.class.php',
         'ModuleInstanceBrowserTable' => '/lib/module_instance_manager/component/module_instance_browser/module_instance_browser_table.class.php',
         'ModuleInstanceBrowserTableCellRenderer' => '/lib/module_instance_manager/component/module_instance_browser/module_instance_browser_table_cell_renderer.class.php',
         'ModuleInstanceBrowserTableColumnModel' => '/lib/module_instance_manager/component/module_instance_browser/module_instance_browser_table_column_model.class.php',
         'ModuleInstanceBrowserTableDataProvider' => '/lib/module_instance_manager/component/module_instance_browser/module_instance_browser_table_data_provider.class.php',
         'ModuleInstanceForm' => '/lib/module_instance_manager/forms/module_instance_form.class.php',
         'DefaultModuleInstanceTableCellRenderer' => '/lib/module_instance_manager/module_instance_table/default_module_instance_table_cell_renderer.class.php',
         'DefaultModuleInstanceTableColumnModel' => '/lib/module_instance_manager/module_instance_table/default_module_instance_table_column_model.class.php',
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
         'DiscoveryInstaller' => '/package/install/discovery_installer.class.php',
         'SettingsConnector' => '/settings/connector.class.php'
    );

    /**
     * Try to load the class
     *
     * @param $classname string
     * @return boolean
     */
    static function load($classname)
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
    static function synch($update)
    {
        return \common\libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }

}
?>