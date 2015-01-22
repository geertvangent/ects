<?php
namespace Ehb\Application\Atlantis\Application\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Atlantis\Application\Manager;

class RightsManagerComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(self :: PARAM_APPLICATION_ID, Request :: get(self :: PARAM_APPLICATION_ID));
        \Chamilo\Libraries\Architecture\Application\Application :: launch(
            \Ehb\Application\Atlantis\Application\Right\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
