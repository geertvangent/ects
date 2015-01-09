<?php
namespace Chamilo\Application\Atlantis\role\component;

use libraries\platform\Request;

class EntitlementComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(
            \application\atlantis\role\Manager :: PARAM_ROLE_ID, 
            Request :: get(\application\atlantis\role\Manager :: PARAM_ROLE_ID));
        \libraries\architecture\Application :: launch(
            \application\atlantis\role\entitlement\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
