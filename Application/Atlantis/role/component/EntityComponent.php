<?php
namespace Chamilo\Application\Atlantis\Role\Component;

class EntityComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application :: launch(
            \Chamilo\Application\Atlantis\Role\Entity\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
