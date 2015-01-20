<?php
namespace Ehb\Application\Sync\Package;

/**
 *
 * @author Hans De Bisschop
 */
class Installer extends \Chamilo\Configuration\Package\Installer
{

    public function get_additional_installers()
    {
        $installers = array();
        $installers[] = new \Ehb\Application\Sync\Bamaflex\Package\Installer($this->get_form_values());
        $installers[] = new \Ehb\Application\Sync\Atlantis\Package\Installer($this->get_form_values());
        $installers[] = new \Ehb\Application\Sync\Cas\Package\Installer($this->get_form_values());
        return $installers;
    }
}
