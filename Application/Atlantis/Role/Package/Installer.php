<?php
namespace Ehb\Application\Atlantis\Role\Package;

/**
 * Atlantis application
 *
 * @package application.atlantis
 */
class Installer extends \Chamilo\Configuration\Package\Action\Installer
{

    public static function get_additional_packages()
    {
        $installers = array();
        $installers[] = 'Ehb\Application\Atlantis\Role\Entitlement';
        $installers[] = 'Ehb\Application\Atlantis\Role\Entity';
        return $installers;
    }
}
