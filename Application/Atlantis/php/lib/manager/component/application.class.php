<?php
namespace application\atlantis;

class ApplicationComponent extends Manager
{

    function run()
    {
        \application\atlantis\application\Manager :: launch($this);
    }
}
?>