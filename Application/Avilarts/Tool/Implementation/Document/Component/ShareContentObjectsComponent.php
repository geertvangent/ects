<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Document\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Document\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class ShareContentObjectsComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
