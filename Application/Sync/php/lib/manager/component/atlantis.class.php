<?php
namespace application\ehb_sync;

use libraries\NotAllowedException;
use libraries\DelegateComponent;

class AtlantisComponent extends Manager implements DelegateComponent
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

        \libraries\Application :: launch(\application\ehb_sync\atlantis\Manager :: context(), $this->get_user(), $this);
    }
}
