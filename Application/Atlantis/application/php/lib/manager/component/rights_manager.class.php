<?php
namespace application\atlantis\application;

use common\libraries\Request;

class RightsManagerComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(self :: PARAM_APPLICATION_ID, Request :: get(self :: PARAM_APPLICATION_ID));
        \application\atlantis\application\right\Manager :: launch($this);
    }
}
