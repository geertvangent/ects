<?php
namespace application\discovery\data_source\doctrine;

class Autoloader
{

    /**
     * The array mapping class names to paths
     * 
     * @var multitype:string
     */
    private static $map = array(
        'Autoloader' => '/autoloader.class.php', 
        'Connection' => '/lib/connection.class.php', 
        'DataSource' => '/lib/data_source.class.php', 
        'SettingsConnector' => '/settings/settings_connector.class.php');

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
