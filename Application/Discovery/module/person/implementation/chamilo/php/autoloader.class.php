<?php
namespace application\discovery\module\person\implementation\chamilo;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Module' => '/lib/module.class.php',
         'DataSource' => '/lib/data_manager/data_source.class.php',
         'UserBrowserTable' => '/lib/user_browser/user_browser_table.class.php',
         'UserBrowserTableCellRenderer' => '/lib/user_browser/user_browser_table_cell_renderer.class.php',
         'UserBrowserTableColumnModel' => '/lib/user_browser/user_browser_table_column_model.class.php',
         'UserBrowserTableDataProvider' => '/lib/user_browser/user_browser_table_data_provider.class.php',
         'application\discovery\module\profile\implementation\chamilo\SettingsConnector' => '/settings/settings_connector.class.php',
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