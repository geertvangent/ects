<?php
namespace Ehb\Application\Atlantis\Role\Component;

use Ehb\Application\Atlantis\Role\Manager;

class EntityComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application\Application :: launch(
            \Ehb\Application\Atlantis\Role\Entity\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
