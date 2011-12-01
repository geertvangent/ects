<?php
namespace application\discovery\module\profile\implementation\bamaflex;

class Autoloader
{

    private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'BamaflexAjaxPlatformGroupsFeed' => '/ajax/platform_groups_feed.class.php',
         'BamaflexAjaxUsersFeed' => '/ajax/users_feed.class.php',
         'Address' => '/lib/address.class.php',
         'Birth' => '/lib/birth.class.php',
         'Module' => '/lib/module.class.php',
         'Nationality' => '/lib/nationality.class.php',
         'Parameters' => '/lib/parameters.class.php',
         'PreviousCollege' => '/lib/previous_college.class.php',
         'PreviousUniversity' => '/lib/previous_university.class.php',
         'Profile' => '/lib/profile.class.php',
         'Rights' => '/lib/rights.class.php',
         'RightsGroupEntityRight' => '/lib/rights_group_entity_right.class.php',
         'DataSource' => '/lib/data_manager/data_source.class.php',
         'RightsPlatformGroupEntity' => '/lib/rights_entity/platform_group.class.php',
         'RightsUserEntity' => '/lib/rights_entity/user.class.php',
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