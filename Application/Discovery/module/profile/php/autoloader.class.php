<?php
namespace application\discovery\module\profile;

class Autoloader
{

    /**
     * The array mapping class names to paths
     *
     * @var string[]
     */
     private static $map = array(
         'Autoloader' => '/autoloader.class.php',
         'Communication' => '/lib/communication.class.php',
         'DataManager' => '/lib/data_manager.class.php',
         'Email' => '/lib/email.class.php',
         'IdentificationCode' => '/lib/identification_code.class.php',
         'Module' => '/lib/module.class.php',
         'Name' => '/lib/name.class.php',
         'Parameters' => '/lib/parameters.class.php',
         'Photo' => '/lib/photo.class.php',
         'Profile' => '/lib/profile.class.php',
         'Rendition' => '/lib/rendition/rendition.class.php',
         'HtmlRendition' => '/lib/rendition/format/html.class.php',
         'HtmlDefaultRendition' => '/lib/rendition/view/html/default.class.php'
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