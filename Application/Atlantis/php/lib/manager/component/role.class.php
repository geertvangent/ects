<?php
namespace application\atlantis;

class RoleComponent extends Manager
{

    public function run()
    {
        \application\atlantis\role\Manager :: launch($this);
    }
}
