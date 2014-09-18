<?php
namespace application\ehb_sync;

use libraries\NotAllowedException;
use libraries\DelegateComponent;

class CasComponent extends Manager implements DelegateComponent
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

        \libraries\Application :: launch(\application\ehb_sync\cas\Manager :: context(), $this->get_user(), $this);
    }
}
