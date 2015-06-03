<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Description\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Description\Manager;
use Chamilo\Libraries\Platform\Session\Request;

class MoverComponent extends Manager
{

    public function get_move_direction()
    {
        return Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_MOVE_DIRECTION);
    }
}
