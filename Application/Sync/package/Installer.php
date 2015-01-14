<?php
namespace Chamilo\Application\EhbSync\Package;

/**
 *
 * @author Hans De Bisschop
 */
use Chamilo\Libraries\Architecture\WebApplicationInstaller;

class Installer extends WebApplicationInstaller
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
