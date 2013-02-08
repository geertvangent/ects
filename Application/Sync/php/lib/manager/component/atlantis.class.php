<?php
namespace application\ehb_sync;

use common\libraries\NotAllowedException;

use common\libraries\DelegateComponent;

class AtlantisComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }
        \application\ehb_sync\atlantis\Manager :: launch($this);
    }

}
