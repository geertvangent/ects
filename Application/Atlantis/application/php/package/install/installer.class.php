<?php
namespace application\atlantis\application;

/**
 * Atlantis application
 * 
 * @package application.atlantis
 */
class Installer extends \common\libraries\package\Installer
{

    /**
     * Constructor
     */
    public function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }
    
    // function install_extra()
    // {
    // $application = new Application();
    // $application->set_name('Discovery');
    // $application->set_description('discovery');
    // $application->create();
    
    // $application = new Application();
    // $application->set_name('Atlantis');
    // $application->set_description('atlantis');
    // $application->create();
    
    // $application = new Application();
    // $application->set_name('PersonalCalendar');
    // $application->set_description('personal calendar');
    // $application->create();
    
    // return true;
    // }
    public function get_additional_installers()
    {
        $installers = array();
        
        $installers[] = new \application\atlantis\application\right\Installer($this->get_form_values());
        
        return $installers;
    }
}
