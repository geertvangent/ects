<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Calendar\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Avilarts\Tool\Implementation\Calendar\Manager;

class MoverComponent extends Manager
{

    public function get_move_direction()
    {
        return Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_MOVE_DIRECTION);
    }
}
