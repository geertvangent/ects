<?php
namespace application\discovery\module\photo\implementation\bamaflex;

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
         'GalleryBrowserTable' => '/lib/gallery_browser/gallery_browser_table.class.php',
         'GalleryBrowserTableCellRenderer' => '/lib/gallery_browser/gallery_browser_table_cell_renderer.class.php',
         'GalleryBrowserTableDataProvider' => '/lib/gallery_browser/gallery_browser_table_data_provider.class.php',
         'GalleryBrowserTablePropertyModel' => '/lib/gallery_browser/gallery_browser_table_property_model.class.php',
         'RenditionImplementation' => '/lib/rendition/rendition.class.php',
         'HtmlDefaultRenditionImplementation' => '/lib/rendition/html/default.class.php',
         'HtmlXlsxRenditionImplementation' => '/lib/rendition/html/xlsx.class.php',
         'HtmlZipRenditionImplementation' => '/lib/rendition/html/zip.class.php',
         'XlsxDefaultRenditionImplementation' => '/lib/rendition/xlsx/default.class.php',
         'ZipDefaultRenditionImplementation' => '/lib/rendition/zip/default.class.php',
         'DefaultGalleryTableCellRenderer' => '/lib/tables/photo_gallery_table/default_gallery_table_cell_renderer.class.php',
         'DefaultGalleryTablePropertyModel' => '/lib/tables/photo_gallery_table/default_gallery_table_property_model.class.php',
         'SettingsConnector' => '/settings/settings_connector.class.php'
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