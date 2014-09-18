<?php
namespace application\atlantis\role;

class EntityComponent extends Manager
{

    public function run()
    {
        \libraries\Application :: launch(
            \application\atlantis\role\entity\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
