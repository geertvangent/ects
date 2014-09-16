<?php
namespace application\atlantis\rights;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var string[]
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Rights' => '/lib/rights.class.php',
         'RightsForm' => '/lib/form/rights.class.php',
         'RightsGroupForm' => '/lib/form/rights_group.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'AccessorComponent' => '/lib/manager/component/accessor.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'CreatorComponent' => '/lib/manager/component/creator.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'DataManager' => '/lib/storage/data_manager.class.php',
         'RightsLocation' => '/lib/storage/data_class/rights_location.class.php',
         'RightsLocationEntityRight' => '/lib/storage/data_class/rights_location_entity_right.class.php',
         'RightsLocationEntityRightGroup' => '/lib/storage/data_class/rights_location_entity_right_group.class.php',
         'EntityTable' => '/lib/table/entity/table.class.php',
         'EntityTableCellRenderer' => '/lib/table/entity/table_cell_renderer.class.php',
         'EntityTableColumnModel' => '/lib/table/entity/table_column_model.class.php',
         'EntityTableDataProvider' => '/lib/table/entity/table_data_provider.class.php',
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