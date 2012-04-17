<?php
namespace application\atlantis\role;

use common\libraries\Request;

class EntityComponent extends Manager
{

    function run()
    {
        \application\atlantis\role\entity\Manager :: launch($this);
    }
}
?>