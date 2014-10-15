<?php
namespace application\atlantis;

class RightsComponent extends Manager
{

    public function run()
    {
        \libraries\architecture\Application :: launch(\application\atlantis\rights\Manager :: context(), $this->get_user(), $this);
    }
}
