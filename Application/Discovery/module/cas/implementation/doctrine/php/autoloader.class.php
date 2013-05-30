<?php
namespace application\discovery\module\cas\implementation\doctrine;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var multitype:string
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Action' => '/lib/action.class.php',
         'Application' => '/lib/application.class.php',
         'Cas' => '/lib/cas.class.php',
         'CasCount' => '/lib/cas_count.class.php',
         'GraphRenderer' => '/lib/graph_renderer.class.php',
         'Module' => '/lib/module.class.php',
         'Parameters' => '/lib/parameters.class.php',
         'Rights' => '/lib/rights.class.php',
         'DataSource' => '/lib/data_manager/data_source.class.php',
         'RenditionImplementation' => '/lib/rendition/rendition.class.php',
         'HtmlDefaultRenditionImplementation' => '/lib/rendition/html/default.class.php',
         'HtmlXlsxRenditionImplementation' => '/lib/rendition/html/xlsx.class.php',
         'XlsxDefaultRenditionImplementation' => '/lib/rendition/xlsx/default.class.php',
         'SettingsConnector' => '/settings/settings_connector.class.php'
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