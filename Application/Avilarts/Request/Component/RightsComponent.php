<?php
namespace Ehb\Application\Avilarts\Request\Component;

use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Ehb\Application\Avilarts\Request\Manager;

class RightsComponent extends Manager
{

    function run()
    {
        $factory = new ApplicationFactory(
            \Ehb\Application\Avilarts\Request\Rights\Manager :: context(),
            new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
        return $factory->run();
    }
}