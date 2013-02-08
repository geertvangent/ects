<?php
namespace application\atlantis;

use common\libraries\WebApplicationInstaller;

/**
 * Atlantis application
 *
 * @package application.atlantis
 */
class Installer extends WebApplicationInstaller
{

    /**
     * Constructor
     */
    function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }

    function get_additional_installers()
    {
        $installers = array();

        $installers[] = new \application\atlantis\application\Installer($this->get_form_values());
        $installers[] = new \application\atlantis\role\Installer($this->get_form_values());
        $installers[] = new \application\atlantis\context\Installer($this->get_form_values());

        return $installers;
    }
}
