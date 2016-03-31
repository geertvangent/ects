<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Calendar\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Avilarts\Tool\Implementation\Calendar\Manager;

class PublisherComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(
            \Chamilo\Core\Repository\Viewer\Manager :: PARAM_ID,
            \Chamilo\Core\Repository\Viewer\Manager :: PARAM_ACTION);
    }
}
