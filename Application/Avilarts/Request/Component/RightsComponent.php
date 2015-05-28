<?php
namespace Ehb\Application\Avilarts\Request\Component;

use Ehb\Application\Avilarts\Request\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;

class RightsComponent extends Manager
{

    function run()
    {
        $factory = new ApplicationFactory(
            $this->getRequest(),
            \Chamilo\Application\Weblcms\Request\Rights\Manager :: context(),
            $this->get_user(),
            $this);
        return $factory->run();
    }
}