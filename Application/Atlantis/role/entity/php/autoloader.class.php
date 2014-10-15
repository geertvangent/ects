<?php
namespace application\atlantis\role\entity;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var string[]
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'AjaxContextsFeed' => '/ajax/contexts_feed.class.php',
         'AjaxPlatformGroupEntityFeed' => '/ajax/platform_group_entity_feed.class.php',
         'AjaxUserEntityFeed' => '/ajax/user_entity_feed.class.php',
         'PlatformGroupEntity' => '/lib/entities/platform_group_entity.class.php',
         'UserEntity' => '/lib/entities/user_entity.class.php',
         'EntityForm' => '/lib/form/entity.class.php',
         'Manager' => '/lib/manager/manager.class.php',
         'BrowserComponent' => '/lib/manager/component/browser.class.php',
         'CreatorComponent' => '/lib/manager/component/creator.class.php',
         'DeleterComponent' => '/lib/manager/component/deleter.class.php',
         'DataManager' => '/lib/storage/data_manager.class.php',
         'RoleEntity' => '/lib/storage/data_class/role_entity.class.php',
         'RoleEntityTracker' => '/lib/storage/data_class/role_entity_tracker.class.php',
         'RoleEntityTable' => '/lib/table/role_entity/table.class.php',
         'RoleEntityTableCellRenderer' => '/lib/table/role_entity/table_cell_renderer.class.php',
         'RoleEntityTableColumnModel' => '/lib/table/role_entity/table_column_model.class.php',
         'RoleEntityTableDataProvider' => '/lib/table/role_entity/table_data_provider.class.php',
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