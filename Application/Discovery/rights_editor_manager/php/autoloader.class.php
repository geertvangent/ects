<?php
namespace application\discovery\rights_editor_manager;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var multitype:string
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'RightsEditorManager' => '/rights_editor_manager.class.php',
         'WeblcmsAjaxEntityRightLocation' => '/ajax/entity_right_location.class.php',
         'RightsEditorManagerAdvancedRightsEditorComponent' => '/component/advanced_rights_editor.class.php',
         'RightsEditorManagerManagerComponent' => '/component/manager.class.php',
         'GroupRightBrowserTable' => '/component/group_right_browser/group_right_browser_table.class.php',
         'GroupRightBrowserTableCellRenderer' => '/component/group_right_browser/group_right_browser_table_cell_renderer.class.php',
         'GroupRightBrowserTableColumnModel' => '/component/group_right_browser/group_right_browser_table_column_model.class.php',
         'GroupRightBrowserTableDataProvider' => '/component/group_right_browser/group_right_browser_table_data_provider.class.php',
         'UserRightBrowserTable' => '/component/user_right_browser/user_right_browser_table.class.php',
         'UserRightBrowserTableCellRenderer' => '/component/user_right_browser/user_right_browser_table_cell_renderer.class.php',
         'UserRightBrowserTableColumnModel' => '/component/user_right_browser/user_right_browser_table_column_model.class.php',
         'UserRightBrowserTableDataProvider' => '/component/user_right_browser/user_right_browser_table_data_provider.class.php',
         'ManageForm' => '/forms/manage_form.class.php'
    );

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
?>