<?php
namespace Ehb\Application\Avilarts\Component;

use Ehb\Application\Avilarts\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;

class RequestComponent extends Manager /* implements DelegateComponent */
{

    public function run()
    {
        $factory = new ApplicationFactory(
            $this->getRequest(),
            \Chamilo\Application\Weblcms\Request\Manager :: context(),
            $this->get_user(),
            $this);
        return $factory->run();
    }
}