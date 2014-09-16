<?php
namespace application\atlantis\application\right;

/**
 * Atlantis application
 * 
 * @package application.atlantis
 */
class Installer extends \configuration\package\Installer
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
    // $right = new Right();
    // $right->set_name('Read');
    // $right->set_description('read');
    // $right->set_application_id(1);
    // $right->create();
    
    // $right = new Right();
    // $right->set_name('Write');
    // $right->set_description('write');
    // $right->set_application_id(1);
    // $right->create();
    
    // $right = new Right();
    // $right->set_name('Read');
    // $right->set_description('read');
    // $right->set_application_id(2);
    // $right->create();
    
    // $right = new Right();
    // $right->set_name('Read');
    // $right->set_description('read');
    // $right->set_application_id(3);
    // $right->create();
    
    // $right = new Right();
    // $right->set_name('Test');
    // $right->set_description('test');
    // $right->set_application_id(1);
    // $right->create();
    
    // $right = new Right();
    // $right->set_name('Test');
    // $right->set_description('test');
    // $right->set_application_id(2);
    // $right->create();
    
    // return true;
    // }
}
