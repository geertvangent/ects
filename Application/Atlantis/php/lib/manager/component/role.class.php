<?php
namespace application\atlantis;


class RoleComponent extends Manager
{

    function run()
    {
        \application\atlantis\role\Manager :: launch($this);
    }
}
