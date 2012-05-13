<?php
namespace application\atlantis\role;

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
        $role = new Role();
        $role->set_name('Docent');
        $role->set_description('docent');
        $role->create();
        
        $role = new Role();
        $role->set_name('Diensthoofd');
        $role->set_description('diensthoofd');
        $role->create();
        
        $role = new Role();
        $role->set_name('Opleidingshoofd');
        $role->set_description('opleidinghoofd');
        $role->create();

        return true;
    }
}
?>