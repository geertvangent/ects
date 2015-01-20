<?php
namespace Ehb\Application\Sync\Component;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Sync\Manager;

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

        \Chamilo\Libraries\Architecture\Application\Application :: launch(\Ehb\Application\Sync\Data\Manager :: context(), $this->get_user(), $this);
    }
}
