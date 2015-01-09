<?php
namespace Chamilo\Application\Atlantis\component;

class ApplicationComponent extends Manager
{

    public function run()
    {
        \libraries\architecture\Application :: launch(
            \application\atlantis\application\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
