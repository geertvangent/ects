<?php
namespace Chamilo\Application\Atlantis\component;

class ContextComponent extends Manager
{

    public function run()
    {
        \libraries\architecture\Application :: launch(\application\atlantis\context\Manager :: context(), $this->get_user(), $this);
    }
}
