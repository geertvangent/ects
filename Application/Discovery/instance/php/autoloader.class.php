<?php
namespace application\discovery\instance;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var multitype:string
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'DataManager' => '/lib/data_manager.class.php',
         'Instance' => '/lib/data_class/instance.class.php',
         'InstanceSetting' => '/lib/data_class/instance_setting.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'InstanceForm' => '/lib/form/instance.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'ActivatorComponent' => '/lib/manager/component/activator.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'CreatorComponent' => '/lib/manager/component/creator.class.php',
         'DeactivatorComponent' => '/lib/manager/component/deactivator.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'MoverComponent' => '/lib/manager/component/mover.class.php',
         'UpdaterComponent' => '/lib/manager/component/updater.class.php',
         'InstanceBrowserTable' => '/lib/manager/component/browser/table.class.php',
         'InstanceBrowserTableCellRenderer' => '/lib/manager/component/browser/table_cell_renderer.class.php',
         'InstanceBrowserTableColumnModel' => '/lib/manager/component/browser/table_column_model.class.php',
         'InstanceBrowserTableDataProvider' => '/lib/manager/component/browser/table_data_provider.class.php',
         'DefaultInstanceTableCellRenderer' => '/lib/table/instance/default_table_cell_renderer.class.php',
         'DefaultInstanceTableColumnModel' => '/lib/table/instance/default_table_column_model.class.php',
         'Activator' => '/package/activate/activator.class.php',
         'Deactivator' => '/package/deactivate/deactivator.class.php',
         'Installer' => '/package/install/installer.class.php'
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