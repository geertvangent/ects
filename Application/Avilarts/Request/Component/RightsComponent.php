<?php
namespace Ehb\Application\Avilarts\Request\Component;

use Ehb\Application\Avilarts\Request\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;

class RightsComponent extends Manager
{

    function run()
    {
        $factory = new ApplicationFactory(
            \Chamilo\Application\Weblcms\Request\Rights\Manager :: context(),
            new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
        return $factory->run();
    }
}