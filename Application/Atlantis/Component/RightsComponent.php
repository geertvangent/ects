<?php
namespace Ehb\Application\Atlantis\Component;

use Ehb\Application\Atlantis\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;

class RightsComponent extends Manager
{

    public function run()
    {
        $factory = new ApplicationFactory(
            \Ehb\Application\Atlantis\Rights\Manager :: context(),
            new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
        $factory->run();
    }
}
