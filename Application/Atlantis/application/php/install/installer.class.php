<?php
namespace application\atlantis\application;

/**
 * Atlantis application
 *
 * @package application.atlantis
 */
class Installer extends \common\libraries\Installer
{

    /**
     * Constructor
     */
    function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }

    function install_extra()
    {
        $application = new Application();
        $application->set_name('Discovery');
        $application->set_description('discovery');
        $application->create();
        
        $application = new Application();
        $application->set_name('Atlantis');
        $application->set_description('atlantis');
        $application->create();
        
        $application = new Application();
        $application->set_name('PersonalCalendar');
        $application->set_description('personal calendar');
        $application->create();
        
        if (! $this->create_root())
        {
            return false;
        }
        return true;
    }
}
?>