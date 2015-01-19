<?php
namespace Chamilo\Application\Atlantis\Component;
use Chamilo\Application\Atlantis\Manager;
class RightsComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application\Application :: launch(\Chamilo\Application\Atlantis\Rights\Manager :: context(), $this->get_user(), $this);
    }
}
