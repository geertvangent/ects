<?php
namespace Chamilo\Application\Discovery\Package;



/**
 *
 * @author Hans De Bisschop
 * @package application.discovery
 */
class Installer extends \Chamilo\Configuration\Package\Installer
{

    public static function get_additional_packages()
    {
        $installers = array();
        $installers[] = 'application\discovery\instance';

        return $installers;
    }
}
