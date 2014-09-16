<?php
namespace application\atlantis\upgrade\v4_0_0;

use configuration\DataManager;

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
        parent :: __construct(DataManager :: get_instance());
    }

    /*
     * (non-PHPdoc) @see \libraries\package\Upgrader::run()
     */
    public function run()
    {
        $data_manager = $this->get_data_manager();

        $success = $data_manager->transactional(
            function ($c) use($data_manager)
            {
                $package = \configuration\package\Package :: get('application\atlantis');
                $registration = DataManager :: get_registration('application\atlantis');
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
