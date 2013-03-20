<?php
namespace application\atlantis\role;

use common\libraries\Request;

class EntitlementComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(
            \application\atlantis\role\Manager :: PARAM_ROLE_ID, 
            Request :: get(\application\atlantis\role\Manager :: PARAM_ROLE_ID));
        \application\atlantis\role\entitlement\Manager :: launch($this);
    }
}
