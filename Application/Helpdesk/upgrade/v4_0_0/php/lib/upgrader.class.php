<?php
namespace application\ehb_helpdesk\upgrade\v4_0_0;

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
     * (non-PHPdoc) @see \libraries\package\Upgrader::run()
     */
    public function run()
    {
        $data_manager = $this->get_data_manager();

        $success = $data_manager->transactional(
            function ($c) use($data_manager)
            {
                $package = \configuration\package\Package :: get('application\ehb_helpdesk');
                $registration = \configuration\DataManager :: get_registration('application\ehb_helpdesk');
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
