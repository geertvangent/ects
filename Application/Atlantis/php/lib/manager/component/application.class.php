<?php
namespace application\atlantis;

class ApplicationComponent extends Manager
{

    public function run()
    {
        \application\atlantis\application\Manager :: launch($this);
    }
}
