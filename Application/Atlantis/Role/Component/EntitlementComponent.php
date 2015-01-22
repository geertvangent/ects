<?php
namespace Ehb\Application\Atlantis\Role\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Atlantis\Role\Manager;

class EntitlementComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(
            \Ehb\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID, 
            Request :: get(\Ehb\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID));
        \Chamilo\Libraries\Architecture\Application\Application :: launch(
            \Ehb\Application\Atlantis\Role\Entitlement\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
