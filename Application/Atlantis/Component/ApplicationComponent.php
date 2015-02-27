<?php
namespace Ehb\Application\Atlantis\Component;

use Ehb\Application\Atlantis\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;

class ApplicationComponent extends Manager
{

    public function run()
    {
        $factory = new ApplicationFactory(
            $this->getRequest(),
            \Ehb\Application\Atlantis\Application\Manager :: context(),
            $this->get_user(),
            $this);
        $factory->run();
    }
}
