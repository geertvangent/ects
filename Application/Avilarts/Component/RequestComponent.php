<?php
namespace Ehb\Application\Avilarts\Component;

use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Ehb\Application\Avilarts\Manager;

class RequestComponent extends Manager /* implements DelegateComponent */
{

    public function run()
    {
        $factory = new ApplicationFactory(
            \Ehb\Application\Avilarts\Request\Manager :: context(),
            new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
        return $factory->run();
    }
}