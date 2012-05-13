<?php
namespace application\atlantis\application;

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
         'Application' => '/lib/data_class/application.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'ApplicationForm' => '/lib/form/application.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'CreatorComponent' => '/lib/manager/component/creator.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'EditorComponent' => '/lib/manager/component/editor.class.php',
         'ListerComponent' => '/lib/manager/component/lister.class.php',
         'RightsComponent' => '/lib/manager/component/rights.class.php',
         'RightsManagerComponent' => '/lib/manager/component/rights_manager.class.php',
         'ApplicationTable' => '/lib/table/application/application_table.class.php',
         'ApplicationTableCellRenderer' => '/lib/table/application/application_table_cell_renderer.class.php',
         'ApplicationTableColumnModel' => '/lib/table/application/application_table_column_model.class.php',
         'ApplicationTableDataProvider' => '/lib/table/application/application_table_data_provider.class.php',
         'Installer' => '/package/install/installer.class.php'
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