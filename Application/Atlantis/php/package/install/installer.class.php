<?php
namespace application\atlantis;

use libraries\WebApplicationInstaller;

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

    public function get_additional_packages()
    {
        $installers = array();
        $installers[] = 'application\atlantis\application';
        $installers[] = 'application\atlantis\role';
        $installers[] = 'application\atlantis\context';
        $installers[] = 'application\atlantis\rights';
        $installers[] = 'application\atlantis\user_group';
        
        return $installers;
    }
}
