<?php
namespace application\atlantis\application\right;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Installer' => '/install/installer.class.php',
         'DataManager' => '/lib/data_manager.class.php',
         'Right' => '/lib/data_class/right.class.php',
         'DoctrineDataManager' => '/lib/data_manager/doctrine.class.php',
         'Mdb2DataManager' => '/lib/data_manager/mdb2.class.php',
         'RightForm' => '/lib/form/right.class.php',
         'RoleForm' => '/lib/form/role.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'AddRoleComponent' => '/lib/manager/component/add_role.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'CreatorComponent' => '/lib/manager/component/creator.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'EditorComponent' => '/lib/manager/component/editor.class.php',
         'RightsComponent' => '/lib/manager/component/rights.class.php',
         'ViewerComponent' => '/lib/manager/component/viewer.class.php',
         'RightTable' => '/lib/table/right/right_table.class.php',
         'RightTableCellRenderer' => '/lib/table/right/right_table_cell_renderer.class.php',
         'RightTableColumnModel' => '/lib/table/right/right_table_column_model.class.php',
         'RightTableDataProvider' => '/lib/table/right/right_table_data_provider.class.php',
    );

    static function load($classname)
    {
        if (isset(self::$map[$classname]))
        {
            require_once __DIR__ . self::$map[$classname];
            return true;
        }

        return false;
   }

   static function synch($update){
        return \common\libraries\AutoloaderUtilities::synch(__DIR__, __DIR__, $update);
   }

}
?>