<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\StreamingVideo\Component;

use Ehb\Application\Avilarts\Tool\Implementation\StreamingVideo\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class ViewerComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
