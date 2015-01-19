<?php
namespace Chamilo\Application\Atlantis\Role\Package;

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
        $installers[] = 'application\atlantis\role\entitlement';
        $installers[] = 'application\atlantis\role\entity';
        return $installers;
    }
}
