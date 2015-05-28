<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\StreamingVideo\Component;

use Ehb\Application\Avilarts\Tool\Implementation\StreamingVideo\Manager;
use Chamilo\Libraries\Platform\Session\Request;

class MoverComponent extends Manager
{

    public function get_move_direction()
    {
        return Request :: get(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_MOVE_DIRECTION);
    }
}
