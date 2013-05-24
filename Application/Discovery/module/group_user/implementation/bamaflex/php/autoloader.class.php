<?php
namespace application\discovery\module\group_user\implementation\bamaflex;

class Autoloader
{

    /**
     * The array mapping class names to paths
     * 
     * @var multitype:string
     */
    private static $map = array(
        'Autoloader' => '/autoloader.class.php', 
        'application\discovery\module\profile\implementation\bamaflex\BamaflexAjaxPlatformGroupsFeed' => '/ajax/platform_groups_feed.class.php', 
        'application\discovery\module\profile\implementation\bamaflex\BamaflexAjaxUsersFeed' => '/ajax/users_feed.class.php', 
        'GroupUser' => '/lib/group_user.class.php', 
        'Module' => '/lib/module.class.php', 
        'Parameters' => '/lib/parameters.class.php', 
        'Rights' => '/lib/rights.class.php', 
        'DataSource' => '/lib/data_manager/data_source.class.php', 
        'RenditionImplementation' => '/lib/rendition/rendition.class.php', 
        'HtmlDefaultRenditionImplementation' => '/lib/rendition/html/default.class.php', 
        'HtmlXlsxRenditionImplementation' => '/lib/rendition/html/xlsx.class.php', 
        'XlsxDefaultRenditionImplementation' => '/lib/rendition/xlsx/default.class.php', 
        'RightsPlatformGroupEntity' => '/lib/rights_entity/platform_group.class.php', 
        'RightsUserEntity' => '/lib/rights_entity/user.class.php', 
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
