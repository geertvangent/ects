<?php
namespace application\atlantis;

class RightComponent extends Manager
{

    function run()
    {
        \application\atlantis\application\right\Manager :: launch($this);
    }
}
