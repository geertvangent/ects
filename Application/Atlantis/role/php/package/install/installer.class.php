<?php
namespace application\atlantis\role;

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
    function __construct($values)
    {
        
        parent :: __construct($values, DataManager :: get_instance());
    }

//     function install_extra()
//     {
//         $role = new Role();
//         $role->set_name('Docent');
//         $role->set_description('docent');
//         $role->create();
        
//         $role = new Role();
//         $role->set_name('Diensthoofd');
//         $role->set_description('diensthoofd');
//         $role->create();
        
//         $role = new Role();
//         $role->set_name('Opleidingshoofd');
//         $role->set_description('opleidinghoofd');
//         $role->create();
        
//         return true;
//     }

    function get_additional_installers()
    {
        $installers = array();
        
        $installers[] = new \application\atlantis\role\entitlement\Installer($this->get_form_values());
        $installers[] = new \application\atlantis\role\entity\Installer($this->get_form_values());
        
        return $installers;
    }
}
?>