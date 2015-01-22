<?php
namespace Ehb\Application\Atlantis\Component;

use Ehb\Application\Atlantis\Manager;

class RightsComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application\Application :: launch(
            \Ehb\Application\Atlantis\Rights\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
