<?php
namespace Ehb\Application\Atlantis\Component;

use Ehb\Application\Atlantis\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;

class RightsComponent extends Manager
{

    public function run()
    {
        $factory = new ApplicationFactory(
            $this->getRequest(),
            \Ehb\Application\Atlantis\Rights\Manager :: context(),
            $this->get_user(),
            $this);
        $factory->run();
    }
}
