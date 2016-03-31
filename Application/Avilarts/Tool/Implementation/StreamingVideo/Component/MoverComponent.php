<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\StreamingVideo\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Avilarts\Tool\Implementation\StreamingVideo\Manager;

class MoverComponent extends Manager
{

    public function get_move_direction()
    {
        return Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_MOVE_DIRECTION);
    }
}
