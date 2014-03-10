<?php
namespace application\atlantis\user_group;

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
         'Context' => '/lib/data_class/context.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'CreatorComponent' => '/lib/manager/component/creator.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'EditorComponent' => '/lib/manager/component/editor.class.php',
         'ViewerComponent' => '/lib/manager/component/viewer.class.php',
         'ApplicationTable' => '/lib/table/application/application_table.class.php',
         'ApplicationTableCellRenderer' => '/lib/table/application/application_table_cell_renderer.class.php',
         'ApplicationTableColumnModel' => '/lib/table/application/application_table_column_model.class.php',
         'ApplicationTableDataProvider' => '/lib/table/application/application_table_data_provider.class.php',
         'Activator' => '/package/activate/activator.class.php',
         'Dectivator' => '/package/deactivate/deactivator.class.php',
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