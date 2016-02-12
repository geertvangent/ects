<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Description\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Description\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class ContentObjectUpdaterComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
