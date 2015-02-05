<?php
namespace Ehb\Application\Sync\Package;

/**
 *
 * @author Hans De Bisschop
 */
class Installer extends \Chamilo\Configuration\Package\Action\Installer
{

    public function get_additional_installers()
    {
        $installers = array();
        $installers[] = new \Ehb\Application\Sync\Package\Installer($this->get_form_values());
        $installers[] = new \Ehb\Application\Sync\Package\Installer($this->get_form_values());
        $installers[] = new \Ehb\Application\Sync\Package\Installer($this->get_form_values());
        return $installers;
    }
}
