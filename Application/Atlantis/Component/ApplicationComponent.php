<?php
namespace Ehb\Application\Atlantis\Component;

use Ehb\Application\Atlantis\Manager;

class ApplicationComponent extends Manager
{

    public function run()
    {
        \Chamilo\Libraries\Architecture\Application\Application :: launch(
            \Ehb\Application\Atlantis\Application\Manager :: context(),
            $this->get_user(),
            $this);
    }
}
