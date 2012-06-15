<?php
namespace application\atlantis;

use common\libraries\DelegateComponent;

class RoleComponent extends Manager
{

    function run()
    {
        \application\atlantis\role\Manager :: launch($this);
    }
}
?>