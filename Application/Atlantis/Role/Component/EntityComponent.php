<?php
namespace Ehb\Application\Atlantis\Role\Component;

use Ehb\Application\Atlantis\Role\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;

class EntityComponent extends Manager
{

    public function run()
    {
        $factory = new ApplicationFactory(
            \Ehb\Application\Atlantis\Role\Entity\Manager :: context(),
            $this->get_user(),
            $this);
        $factory->run();
    }
}
