<?php
namespace Ehb\Application\Atlantis\Component;

use Ehb\Application\Atlantis\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;

class RoleComponent extends Manager
{

    public function run()
    {
        $factory = new ApplicationFactory(\Ehb\Application\Atlantis\Role\Manager :: context(), $this->get_user(), $this);
        $factory->run();
    }
}
