<?php
namespace application\discovery\upgrade\v4_0_0;

use configuration\package\Package;

/**
 *
 * @author Hans De Bisschop
 * @author Magali Gillard
 */
class Upgrader extends \configuration\package\Upgrader
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent :: __construct(\configuration\DataManager :: get_instance());
    }
    
    /*
     * (non-PHPdoc) @see \configuration\package\Upgrader::run()
     */
    public function run()
    {
        $data_manager = $this->get_data_manager();
        
        $success = $data_manager->transactional(
            function ($c) use($data_manager)
            {
                $package = Package :: get('application\discovery');
                $registration = \configuration\DataManager :: get_registration('application\discovery');
                $registration->set_version($package->get_version());
                if (! $registration->update())
                {
                    return false;
                }
                
                return true;
            });
        
        return $success;
    }
}
