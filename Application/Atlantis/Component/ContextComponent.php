<?php
namespace Chamilo\Application\Atlantis\Component;
use Chamilo\Application\Atlantis\Manager;
class ContextComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application\Application :: launch(\Chamilo\Application\Atlantis\Context\Manager :: context(), $this->get_user(), $this);
    }
}
