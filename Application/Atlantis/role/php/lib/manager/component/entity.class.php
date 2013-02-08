<?php
namespace application\atlantis\role;



class EntityComponent extends Manager
{

    function run()
    {
        \application\atlantis\role\entity\Manager :: launch($this);
    }
}
