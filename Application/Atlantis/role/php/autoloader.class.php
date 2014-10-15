<?php
namespace application\atlantis\role;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var string[]
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'AjaxRolesFeed' => '/ajax/roles_feed.class.php',
         'DataManager' => '/lib/data_manager.class.php',
         'Role' => '/lib/data_class/role.class.php',
         'RoleForm' => '/lib/form/role.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'CreatorComponent' => '/lib/manager/component/creator.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'EditorComponent' => '/lib/manager/component/editor.class.php',
         'EntitlementComponent' => '/lib/manager/component/entitlement.class.php',
         'EntityComponent' => '/lib/manager/component/entity.class.php',
         'RightsComponent' => '/lib/manager/component/rights.class.php',
         'ViewerComponent' => '/lib/manager/component/viewer.class.php',
         'RoleTable' => '/lib/table/role/role_table.class.php',
         'RoleTableCellRenderer' => '/lib/table/role/role_table_cell_renderer.class.php',
         'RoleTableColumnModel' => '/lib/table/role/role_table_column_model.class.php',
         'RoleTableDataProvider' => '/lib/table/role/role_table_data_provider.class.php',
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