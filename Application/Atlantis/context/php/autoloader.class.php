<?php
namespace application\atlantis\context;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var string[]
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Menu' => '/lib/menu.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'DataManager' => '/lib/storage/data_manager.class.php',
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
        return \libraries\utilities\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }

}