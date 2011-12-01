<?php
namespace application\discovery\module\profile;

class Autoloader
{
    
    private static $map = array('Autoloader' => '/autoloader.class.php', 
            'Communication' => '/lib/communication.class.php', 'DataManager' => '/lib/data_manager.class.php', 
            'DataManagerInterface' => '/lib/data_manager_interface.class.php', 'Email' => '/lib/email.class.php', 
            'IdentificationCode' => '/lib/identification_code.class.php', 'Module' => '/lib/module.class.php', 
            'Name' => '/lib/name.class.php', 'Parameters' => '/lib/parameters.class.php', 
            'Photo' => '/lib/photo.class.php', 'Profile' => '/lib/profile.class.php');

    static function load($classname)
    {
        if (isset(self :: $map[$classname]))
        {
            require_once __DIR__ . self :: $map[$classname];
            return true;
        }
        
        return false;
    }

    static function synch($update)
    {
        return \common\libraries\AutoloaderUtilities :: synch(__DIR__, __DIR__, $update);
    }

}
?>