<?php
namespace Ehb\Application\Atlantis\Application\Component;

use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Atlantis\Application\Manager;

class RightsManagerComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(self::PARAM_APPLICATION_ID, Request::get(self::PARAM_APPLICATION_ID));
        
        $factory = new ApplicationFactory(
            \Ehb\Application\Atlantis\Application\Right\Manager::context(), 
            new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
        return $factory->run();
    }
}
