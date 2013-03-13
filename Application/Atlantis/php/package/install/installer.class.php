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
    public function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }

    public function get_additional_installers()
    {
        $installers = array();
        
        $installers[] = new \application\atlantis\application\Installer($this->get_form_values());
        $installers[] = new \application\atlantis\role\Installer($this->get_form_values());
        $installers[] = new \application\atlantis\context\Installer($this->get_form_values());
        
        return $installers;
    }
}
