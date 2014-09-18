<?php
namespace application\ehb_sync;

use libraries\NotAllowedException;
use libraries\DelegateComponent;

class DataComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }

        \application\ehb_sync\data\Manager :: launch($this);
    }
}
