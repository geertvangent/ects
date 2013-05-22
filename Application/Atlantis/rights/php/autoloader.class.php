<?php
namespace application\atlantis\rights;

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
        'DataManagerInterface' => '/lib/data_manager_interface.class.php', 
        'Rights' => '/lib/rights.class.php', 
        'LocationEntityRight' => '/lib/data_class/location_entity_right.class.php', 
        'LocationEntityRightGroup' => '/lib/data_class/location_entity_right_group.class.php', 
        'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php', 
        'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php', 
        'RightsForm' => '/lib/form/rights.class.php', 
        'RightsGroupForm' => '/lib/form/rights_group.class.php', 
        'Manager' => '/lib/manager/manager.class.php', 
        'AccessorComponent' => '/lib/manager/component/accessor.class.php', 
        'BrowserComponent' => '/lib/manager/component/browser.class.php', 
        'CreatorComponent' => '/lib/manager/component/creator.class.php', 
        'DeleterComponent' => '/lib/manager/component/deleter.class.php', 
        'EntityTable' => '/lib/table/entity/table.class.php', 
        'EntityTableCellRenderer' => '/lib/table/entity/table_cell_renderer.class.php', 
        'EntityTableColumnModel' => '/lib/table/entity/table_column_model.class.php', 
        'EntityTableDataProvider' => '/lib/table/entity/table_data_provider.class.php', 
        'Activator' => '/package/activate/activator.class.php', 
        'Deactivator' => '/package/deactivate/deactivator.class.php', 
        'Installer' => '/package/install/installer.class.php');

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
