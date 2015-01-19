<?php
namespace Chamilo\Application\Atlantis\Role\Component;

use Chamilo\Application\Atlantis\Role\Manager;

class EntityComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application\Application :: launch(
            \Chamilo\Application\Atlantis\Role\Entity\Manager :: context(),
            $this->get_user(),
            $this);
    }
}
