<?php
namespace Chamilo\Application\EhbSync\Component;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Application\EhbSync\Manager;

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

        \Chamilo\Libraries\Architecture\Application\Application :: launch(\Chamilo\Application\EhbSync\Data\Manager :: context(), $this->get_user(), $this);
    }
}
