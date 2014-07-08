<?php
namespace application\ehb_sync;

/**
 *
 * @author Hans De Bisschop
 */
use common\libraries\WebApplicationInstaller;

class Installer extends WebApplicationInstaller
{

    /**
     * Constructor
     */
    public function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }

    public function get_additional_installers()
    {
        $installers = array();
        $installers[] = new \application\ehb_sync\bamaflex\Installer($this->get_form_values());
        $installers[] = new \application\ehb_sync\atlantis\Installer($this->get_form_values());
        $installers[] = new \application\ehb_sync\cas\Installer($this->get_form_values());
        return $installers;
    }
}
