<?php
namespace Ehb\Application\Atlantis\Package;



/**
 * Atlantis application
 *
 * @package application.atlantis
 */
class Installer extends \Chamilo\Configuration\Package\Installer
{

    public static function get_additional_packages()
    {
        $installers = array();
        $installers[] = 'application\atlantis\application';
        $installers[] = 'application\atlantis\role';
        $installers[] = 'application\atlantis\context';
        $installers[] = 'application\atlantis\rights';
        $installers[] = 'application\atlantis\user_group';

        return $installers;
    }
}
