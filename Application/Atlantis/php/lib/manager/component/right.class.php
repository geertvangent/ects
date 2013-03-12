<?php
namespace application\atlantis;

class RightComponent extends Manager
{

    public function run()
    {
        \application\atlantis\application\right\Manager :: launch($this);
    }
}
