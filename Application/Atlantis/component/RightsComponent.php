<?php
namespace Chamilo\Application\Atlantis\Component;

class RightsComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application :: launch(\Chamilo\Application\Atlantis\Rights\Manager :: context(), $this->get_user(), $this);
    }
}
