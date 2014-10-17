<?php
namespace application\atlantis\application;

use libraries\platform\Request;

class RightsManagerComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(self :: PARAM_APPLICATION_ID, Request :: get(self :: PARAM_APPLICATION_ID));
        \libraries\architecture\Application :: launch(
            \application\atlantis\application\right\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
