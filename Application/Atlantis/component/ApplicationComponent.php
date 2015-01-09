<?php
namespace Chamilo\Application\Atlantis\Component;

class ApplicationComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application :: launch(
            \Chamilo\Application\Atlantis\Application\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
