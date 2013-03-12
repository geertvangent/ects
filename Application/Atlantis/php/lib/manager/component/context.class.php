<?php
namespace application\atlantis;

class ContextComponent extends Manager
{

    public function run()
    {
        \application\atlantis\context\Manager :: launch($this);
    }
}
