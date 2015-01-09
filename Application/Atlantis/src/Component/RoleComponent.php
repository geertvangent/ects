<?php
namespace Chamilo\Application\Atlantis\Component;

class RoleComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application :: launch(\Chamilo\Application\Atlantis\Role\Manager :: context(), $this->get_user(), $this);
    }
}
