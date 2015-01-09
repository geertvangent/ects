<?php
namespace Chamilo\Application\Atlantis\Application\Component;

use Chamilo\Libraries\Platform\Session\Request;

class RightsManagerComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(self :: PARAM_APPLICATION_ID, Request :: get(self :: PARAM_APPLICATION_ID));
        \Chamilo\Libraries\Architecture\Application :: launch(
            \Chamilo\Application\Atlantis\Application\Right\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
