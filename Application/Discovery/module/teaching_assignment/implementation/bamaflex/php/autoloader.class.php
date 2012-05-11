<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var multitype:string
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'BamaflexAjaxPlatformGroupsFeed' => '/ajax/platform_groups_feed.class.php',
         'BamaflexAjaxUsersFeed' => '/ajax/users_feed.class.php',
         'Module' => '/lib/module.class.php',
         'Rights' => '/lib/rights.class.php',
         'TeachingAssignment' => '/lib/teaching_assignment.class.php',
         'DataSource' => '/lib/data_manager/data_source.class.php',
         'RightsPlatformGroupEntity' => '/lib/rights_entity/platform_group.class.php',
         'RightsUserEntity' => '/lib/rights_entity/user.class.php',
         'SettingsConnector' => '/settings/settings_connector.class.php'
    );

    /**
     * Try to load the class
     *
     * @param $classname string
     * @return boolean
     */
    static function load($classname)
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
    static function synch($update)
    {
        return \common\libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }

}
?>