<?php
namespace Application\Discovery\package;



/**
 *
 * @author Hans De Bisschop
 * @package application.discovery
 */
class Installer extends \configuration\package\Installer
{

    public static function get_additional_packages()
    {
        $installers = array();
        $installers[] = 'application\discovery\instance';

        return $installers;
    }
}
