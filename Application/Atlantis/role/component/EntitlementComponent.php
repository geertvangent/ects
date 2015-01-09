<?php
namespace Chamilo\Application\Atlantis\Role\Component;

use Chamilo\Libraries\Platform\Request;

class EntitlementComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(
            \Chamilo\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID, 
            Request :: get(\Chamilo\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID));
        \Chamilo\Libraries\Architecture\Application :: launch(
            \Chamilo\Application\Atlantis\Role\Entitlement\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
