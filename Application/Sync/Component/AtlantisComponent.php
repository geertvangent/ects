<?php
namespace Ehb\Application\Sync\Component;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Sync\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;

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
        $factory = new ApplicationFactory(\Ehb\Application\Sync\Atlantis\Manager :: context(), $this->get_user(), $this);
        $factory->run();
    }
}
