<?php
namespace application\discovery\module\photo\implementation\bamaflex;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'application\discovery\module\profile\implementation\bamaflex\BamaflexAjaxPlatformGroupsFeed' => '/ajax/platform_groups_feed.class.php',
         'application\discovery\module\profile\implementation\bamaflex\BamaflexAjaxUsersFeed' => '/ajax/users_feed.class.php',
         'Module' => '/lib/module.class.php',
         'Rights' => '/lib/rights.class.php',
         'DataSource' => '/lib/data_manager/data_source.class.php',
         'GalleryBrowserTable' => '/lib/gallery_browser/gallery_browser_table.class.php',
         'GalleryBrowserTableCellRenderer' => '/lib/gallery_browser/gallery_browser_table_cell_renderer.class.php',
         'GalleryBrowserTableDataProvider' => '/lib/gallery_browser/gallery_browser_table_data_provider.class.php',
         'GalleryBrowserTablePropertyModel' => '/lib/gallery_browser/gallery_browser_table_property_model.class.php',
         'application\discovery\module\profile\implementation\bamaflex\RightsPlatformGroupEntity' => '/lib/rights_entity/platform_group.class.php',
         'application\discovery\module\profile\implementation\bamaflex\RightsUserEntity' => '/lib/rights_entity/user.class.php',
         'DefaultGalleryTableCellRenderer' => '/lib/tables/photo_gallery_table/default_gallery_table_cell_renderer.class.php',
         'DefaultGalleryTablePropertyModel' => '/lib/tables/photo_gallery_table/default_gallery_table_property_model.class.php',
         'SettingsConnector' => '/settings/settings_connector.class.php',
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