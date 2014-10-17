<?php
namespace application\atlantis\application\right;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var string[]
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'RightForm' => '/lib/form/right.class.php',
         'RoleForm' => '/lib/form/role.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'AddRoleComponent' => '/lib/manager/component/add_role.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'CreatorComponent' => '/lib/manager/component/creator.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'EditorComponent' => '/lib/manager/component/editor.class.php',
         'RightsComponent' => '/lib/manager/component/rights.class.php',
         'DataManager' => '/lib/table/data_manager.class.php',
         'Right' => '/lib/table/data_class/right.class.php',
         'RightTable' => '/lib/table/right/right_table.class.php',
         'RightTableCellRenderer' => '/lib/table/right/right_table_cell_renderer.class.php',
         'RightTableColumnModel' => '/lib/table/right/right_table_column_model.class.php',
         'RightTableDataProvider' => '/lib/table/right/right_table_data_provider.class.php',
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