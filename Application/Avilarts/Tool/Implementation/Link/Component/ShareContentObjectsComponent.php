<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Link\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Link\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class ShareContentObjectsComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
