<?php
namespace application\atlantis;

class RoleComponent extends Manager
{

    public function run()
    {
        \libraries\Application :: launch(\application\atlantis\role\Manager :: context(), $this->get_user(), $this);
    }
}
