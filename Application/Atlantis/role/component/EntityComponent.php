<?php
namespace Chamilo\Application\Atlantis\role\component;

class EntityComponent extends Manager
{

    public function run()
    {
        \libraries\architecture\Application :: launch(
            \application\atlantis\role\entity\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
