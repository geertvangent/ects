<?php
namespace application\atlantis\application;

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
        $installers[] = 'application\atlantis\application\right';
        return $installers;
    }
}
