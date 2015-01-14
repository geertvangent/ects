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
        $installers[] = new \Chamilo\Application\EhbSync\Bamaflex\Installer($this->get_form_values());
        $installers[] = new \Chamilo\Application\EhbSync\Atlantis\Installer($this->get_form_values());
        $installers[] = new \Chamilo\Application\EhbSync\Cas\Installer($this->get_form_values());
        return $installers;
    }
}
