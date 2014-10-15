<?php
namespace application\discovery;

use libraries\architecture\WebApplicationInstaller;

/**
 *
 * @author Hans De Bisschop
 * @package application.discovery
 */
class Installer extends WebApplicationInstaller
{

    public static function get_additional_packages()
    {
        $installers = array();
        $installers[] = 'application\discovery\instance';

        return $installers;
    }
}
