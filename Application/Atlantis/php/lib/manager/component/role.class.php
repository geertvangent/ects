<?php
namespace application\atlantis;

class RoleComponent extends Manager
{

    public function run()
    {
        \libraries\architecture\Application :: launch(\application\atlantis\role\Manager :: context(), $this->get_user(), $this);
    }
}
