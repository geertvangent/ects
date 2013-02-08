<?php
namespace application\atlantis;

class ContextComponent extends Manager
{

    function run()
    {
        \application\atlantis\context\Manager :: launch($this);
    }
}
