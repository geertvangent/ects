<?php
namespace application\atlantis;

class RightsComponent extends Manager
{

    public function run()
    {
        \application\atlantis\rights\Manager :: launch($this);
    }
}
