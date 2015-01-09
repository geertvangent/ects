<?php
namespace Chamilo\Application\Atlantis\role\package;

/**
 * Atlantis application
 *
 * @package application.atlantis
 */
class Installer extends \configuration\package\Installer
{

    public static function get_additional_packages()
    {
        $installers = array();
        $installers[] = 'application\atlantis\role\entitlement';
        $installers[] = 'application\atlantis\role\entity';
        return $installers;
    }
}
