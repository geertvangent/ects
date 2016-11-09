<?php
namespace Ehb\Application\Atlantis\Role\Component;

use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Atlantis\Role\Manager;

class EntitlementComponent extends Manager
{

    public function run()
    {
        $this->set_parameter(
            \Ehb\Application\Atlantis\Role\Manager::PARAM_ROLE_ID, 
            Request::get(\Ehb\Application\Atlantis\Role\Manager::PARAM_ROLE_ID));
        
        $factory = new ApplicationFactory(
            \Ehb\Application\Atlantis\Role\Entitlement\Manager::context(), 
            new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
        return $factory->run();
    }
}
