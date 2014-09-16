<?php
namespace application\atlantis\role\entitlement;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var string[]
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'EntitlementForm' => '/lib/form/entitlement.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'ListerComponent' => '/lib/manager/component/lister.class.php',
         'DataManager' => '/lib/storage/data_manager.class.php',
         'Entitlement' => '/lib/storage/data_class/entitlement.class.php',
         'EntitlementTable' => '/lib/table/entitlement/table.class.php',
         'EntitlementTableCellRenderer' => '/lib/table/entitlement/table_cell_renderer.class.php',
         'EntitlementTableColumnModel' => '/lib/table/entitlement/table_column_model.class.php',
         'EntitlementTableDataProvider' => '/lib/table/entitlement/table_data_provider.class.php',
         'Activator' => '/package/activate/activator.class.php',
         'Deactivator' => '/package/deactivate/deactivator.class.php',
         'Installer' => '/package/install/installer.class.php'
    );

    /**
     * Try to load the class
     *
     * @param string $classname
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
     * @param boolean $update
     * @return string[]
     */
    public static function synch($update)
    {
        return \libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }

}
?>