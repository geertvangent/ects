<?php
namespace Application\EhbSync\component;

use libraries\architecture\NotAllowedException;
use libraries\architecture\DelegateComponent;

class DataComponent extends Manager implements DelegateComponent
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            throw new NotAllowedException();
        }

        \libraries\architecture\Application :: launch(\application\ehb_sync\data\Manager :: context(), $this->get_user(), $this);
    }
}
