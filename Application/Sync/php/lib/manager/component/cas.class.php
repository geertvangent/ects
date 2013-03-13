<?php
namespace application\ehb_sync;

use common\libraries\NotAllowedException;
use common\libraries\DelegateComponent;

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
        \application\ehb_sync\cas\Manager :: launch($this);
    }
}
