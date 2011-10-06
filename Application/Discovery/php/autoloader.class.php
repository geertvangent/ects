<?php
namespace application\discovery;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'DiscoveryInstaller' => '/install/discovery_installer.class.php',
         'DataSource' => '/lib/data_source.class.php',
         'DataSourceInstance' => '/lib/data_source_instance.class.php',
         'DataSourceInstanceSetting' => '/lib/data_source_instance_setting.class.php',
         'DiscoveryDataManager' => '/lib/discovery_data_manager.class.php',
         'DiscoveryDataManagerInterface' => '/lib/discovery_data_manager_interface.class.php',
         'DiscoveryItem' => '/lib/discovery_item.class.php',
         'DiscoveryModuleInstance' => '/lib/discovery_module_instance.class.php',
         'DiscoveryModuleInstanceSetting' => '/lib/discovery_module_instance_setting.class.php',
         'LegendTable' => '/lib/legend_table.class.php',
         'Module' => '/lib/module.class.php',
         'SortableTable' => '/lib/sortable_table.class.php',
         'Mdb2DiscoveryDataManager' => '/lib/data_manager/mdb2.class.php',
         'DiscoveryManager' => '/lib/discovery_manager/discovery_manager.class.php',
         'DiscoveryManagerBrowserComponent' => '/lib/discovery_manager/component/browser.class.php',
         'DiscoveryManagerModuleComponent' => '/lib/discovery_manager/component/module.class.php',
         'DiscoveryManagerViewerComponent' => '/lib/discovery_manager/component/viewer.class.php',
         'UserBrowserTable' => '/lib/discovery_manager/component/user_browser/user_browser_table.class.php',
         'UserBrowserTableCellRenderer' => '/lib/discovery_manager/component/user_browser/user_browser_table_cell_renderer.class.php',
         'UserBrowserTableColumnModel' => '/lib/discovery_manager/component/user_browser/user_browser_table_column_model.class.php',
         'UserBrowserTableDataProvider' => '/lib/discovery_manager/component/user_browser/user_browser_table_data_provider.class.php',
         'DiscoveryModuleInstanceManager' => '/lib/discovery_module_instance_manager/discovery_module_instance_manager.class.php',
         'DiscoveryModuleInstanceManagerActivatorComponent' => '/lib/discovery_module_instance_manager/component/activator.class.php',
         'DiscoveryModuleInstanceManagerBrowserComponent' => '/lib/discovery_module_instance_manager/component/browser.class.php',
         'DiscoveryModuleInstanceManagerCreatorComponent' => '/lib/discovery_module_instance_manager/component/creator.class.php',
         'DiscoveryModuleInstanceManagerDeactivatorComponent' => '/lib/discovery_module_instance_manager/component/deactivator.class.php',
         'DiscoveryModuleInstanceManagerDeleterComponent' => '/lib/discovery_module_instance_manager/component/deleter.class.php',
         'DiscoveryModuleInstanceManagerRightsEditorComponent' => '/lib/discovery_module_instance_manager/component/rights_editor.class.php',
         'DiscoveryModuleInstanceManagerUpdaterComponent' => '/lib/discovery_module_instance_manager/component/updater.class.php',
         'DiscoveryModuleInstanceBrowserTable' => '/lib/discovery_module_instance_manager/component/discovery_module_instance_browser/discovery_module_instance_browser_table.class.php',
         'DiscoveryModuleInstanceBrowserTableCellRenderer' => '/lib/discovery_module_instance_manager/component/discovery_module_instance_browser/discovery_module_instance_browser_table_cell_renderer.class.php',
         'DiscoveryModuleInstanceBrowserTableColumnModel' => '/lib/discovery_module_instance_manager/component/discovery_module_instance_browser/discovery_module_instance_browser_table_column_model.class.php',
         'DiscoveryModuleInstanceBrowserTableDataProvider' => '/lib/discovery_module_instance_manager/component/discovery_module_instance_browser/discovery_module_instance_browser_table_data_provider.class.php',
         'DefaultDiscoveryModuleInstanceTableCellRenderer' => '/lib/discovery_module_instance_manager/discovery_module_instance_table/default_discovery_module_instance_table_cell_renderer.class.php',
         'DefaultDiscoveryModuleInstanceTableColumnModel' => '/lib/discovery_module_instance_manager/discovery_module_instance_table/default_discovery_module_instance_table_column_model.class.php',
         'DiscoveryModuleInstanceForm' => '/lib/discovery_module_instance_manager/forms/discovery_module_instance_form.class.php',
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