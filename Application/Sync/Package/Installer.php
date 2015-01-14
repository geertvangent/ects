<?php
namespace Chamilo\Application\EhbSync\Package;

/**
 *
 * @author Hans De Bisschop
 */
class Installer extends \Chamilo\Configuration\Package\Installer
{

    public function get_additional_installers()
    {
        $installers = array();
        $installers[] = new \Chamilo\Application\EhbSync\Bamaflex\Package\Installer($this->get_form_values());
        $installers[] = new \Chamilo\Application\EhbSync\Atlantis\Package\Installer($this->get_form_values());
        $installers[] = new \Chamilo\Application\EhbSync\Cas\Package\Installer($this->get_form_values());
        return $installers;
    }
}
