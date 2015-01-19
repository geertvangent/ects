<?php
namespace Chamilo\Application\Atlantis\Application\Package;

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
        $installers[] = 'application\atlantis\application\right';
        return $installers;
    }
}
