<?php
namespace application\ehb_sync\upgrade\v4_0_0;

use common\libraries\CommonDataManager;
use common\libraries\package\Package;

/**
 *
 * @author Hans De Bisschop
 * @author Magali Gillard
 */
class Upgrader extends \common\libraries\package\Upgrader
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent :: __construct(CommonDataManager :: get_instance());
    }

    /*
     * (non-PHPdoc) @see \common\libraries\package\Upgrader::run()
     */
    public function run()
    {
        $data_manager = $this->get_data_manager();

        $success = $data_manager->transactional(
            function ($c) use($data_manager)
            {
                $package = Package :: get('application\ehb_sync');
                $registration = CommonDataManager :: get_registration('application\ehb_sync');
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
