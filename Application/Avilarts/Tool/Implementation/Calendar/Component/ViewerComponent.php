<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Calendar\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Calendar\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class ViewerComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
