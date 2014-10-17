<?php
namespace application\atlantis;

class RightComponent extends Manager
{

    public function run()
    {
        \libraries\architecture\Application :: launch(
            \application\atlantis\application\right\Manager :: context(), 
            $this->get_user(), 
            $this);
    }
}
