<?php
namespace Chamilo\Application\Atlantis\Role\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Application\Atlantis\Role\Manager;

class EntitlementComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(
            \Chamilo\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID,
            Request :: get(\Chamilo\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID));
        \Chamilo\Libraries\Architecture\Application\Application :: launch(
            \Chamilo\Application\Atlantis\Role\Entitlement\Manager :: context(),
            $this->get_user(),
            $this);
    }
}
