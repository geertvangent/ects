<?php
namespace application\ehb_sync;

/**
 *
 * @author Hans De Bisschop
 */
use libraries\architecture\WebApplicationInstaller;

class Installer extends WebApplicationInstaller
{

    public function get_additional_installers()
    {
        $installers = array();
        $installers[] = new \application\ehb_sync\bamaflex\Installer($this->get_form_values());
        $installers[] = new \application\ehb_sync\atlantis\Installer($this->get_form_values());
        $installers[] = new \application\ehb_sync\cas\Installer($this->get_form_values());
        return $installers;
    }
}
