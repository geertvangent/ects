<?php
namespace Chamilo\Application\Atlantis\Component;
use Chamilo\Application\Atlantis\Manager;
class RightComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application\Application :: launch(
            \Chamilo\Application\Atlantis\Application\Right\Manager :: context(),
            $this->get_user(),
            $this);
    }
}
