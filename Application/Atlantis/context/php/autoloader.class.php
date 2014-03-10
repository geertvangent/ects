<?php
namespace application\atlantis\context;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var multitype:string
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'ContextAjaxContextsFeed' => '/ajax/contexts_feed.class.php',
         'DataManager' => '/lib/data_manager.class.php',
         'Menu' => '/lib/menu.class.php',
         'Context' => '/lib/data_class/context.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'ContextTable' => '/lib/table/context/table.class.php',
         'ContextTableCellRenderer' => '/lib/table/context/table_cell_renderer.class.php',
         'ContextTableColumnModel' => '/lib/table/context/table_column_model.class.php',
         'ContextTableDataProvider' => '/lib/table/context/table_data_provider.class.php',
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