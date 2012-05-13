<?php
namespace application\atlantis\role\entitlement;

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
         'Entitlement' => '/lib/data_class/entitlement.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'EntitlementForm' => '/lib/form/entitlement.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'ListerComponent' => '/lib/manager/component/lister.class.php',
         'EntitlementTable' => '/lib/table/entitlement/table.class.php',
         'EntitlementTableCellRenderer' => '/lib/table/entitlement/table_cell_renderer.class.php',
         'EntitlementTableColumnModel' => '/lib/table/entitlement/table_column_model.class.php',
         'EntitlementTableDataProvider' => '/lib/table/entitlement/table_data_provider.class.php',
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