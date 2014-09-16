<?php
namespace application\atlantis;

class ApplicationComponent extends Manager
{

    public function run()
    {
        \libraries\Application :: launch(
            \application\atlantis\application\Manager :: context(),
            $this->get_user(),
            $this);
    }
}
