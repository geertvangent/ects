<?php
namespace Chamilo\Application\Atlantis\Component;

class RightComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application :: launch(
            \Chamilo\Application\Atlantis\Application\Right\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
