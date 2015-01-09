<?php
namespace Chamilo\Application\Atlantis\Component;

class ContextComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application :: launch(\Chamilo\Application\Atlantis\Context\Manager :: context(), $this->get_user(), $this);
    }
}
