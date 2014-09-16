<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

class Autoloader
{

    /**
     * The array mapping class names to paths
     * 
     * @var string[]
     */
    private static $map = array(
        'Autoloader' => '/autoloader.class.php', 
        'Module' => '/lib/module.class.php', 
        'Rights' => '/lib/rights.class.php', 
        'DataSource' => '/lib/data_manager/data_source.class.php', 
        'GroupBrowserTable' => '/lib/group_browser/group_browser_table.class.php', 
        'GroupBrowserTableCellRenderer' => '/lib/group_browser/group_browser_table_cell_renderer.class.php', 
        'GroupBrowserTableColumnModel' => '/lib/group_browser/group_browser_table_column_model.class.php', 
        'GroupBrowserTableDataProvider' => '/lib/group_browser/group_browser_table_data_provider.class.php', 
        'GroupRelUserBrowserTable' => '/lib/group_rel_user_browser/group_rel_user_browser_table.class.php', 
        'GroupRelUserBrowserTableCellRenderer' => '/lib/group_rel_user_browser/group_rel_user_browser_table_cell_renderer.class.php', 
        'GroupRelUserBrowserTableColumnModel' => '/lib/group_rel_user_browser/group_rel_user_browser_table_column_model.class.php', 
        'GroupRelUserBrowserTableDataProvider' => '/lib/group_rel_user_browser/group_rel_user_browser_table_data_provider.class.php', 
        'RenditionImplementation' => '/lib/rendition/rendition.class.php', 
        'HtmlDefaultRenditionImplementation' => '/lib/rendition/html/default.class.php', 
        'HtmlXlsxRenditionImplementation' => '/lib/rendition/html/xlsx.class.php', 
        'XlsxDefaultRenditionImplementation' => '/lib/rendition/xlsx/default.class.php', 
        'RightsPlatformGroupEntity' => '/lib/rights_entity/platform_group.class.php', 
        'RightsUserEntity' => '/lib/rights_entity/user.class.php', 
        'UserBrowserTable' => '/lib/user_browser/user_browser_table.class.php', 
        'UserBrowserTableCellRenderer' => '/lib/user_browser/user_browser_table_cell_renderer.class.php', 
        'UserBrowserTableColumnModel' => '/lib/user_browser/user_browser_table_column_model.class.php', 
        'UserBrowserTableDataProvider' => '/lib/user_browser/user_browser_table_data_provider.class.php', 
        'application\discovery\module\profile\implementation\chamilo\SettingsConnector' => '/settings/settings_connector.class.php');

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