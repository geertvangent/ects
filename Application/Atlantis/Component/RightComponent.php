<?php
namespace Ehb\Application\Atlantis\Component;

use Ehb\Application\Atlantis\Manager;

class RightComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application\Application :: launch(
            \Ehb\Application\Atlantis\Application\Right\Manager :: context(),
            $this->get_user(),
            $this);
    }
}
