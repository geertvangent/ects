<?php
namespace Chamilo\Application\EhbSync\Component;

use Chamilo\Libraries\Architecture\NotAllowedException;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class BamaflexComponent extends Manager implements DelegateComponent
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

        \Chamilo\Libraries\Architecture\Application :: launch(\Chamilo\Application\EhbSync\Bamaflex\Manager :: context(), $this->get_user(), $this);
    }
}
