<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Note\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Note\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class BrowserComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(self :: PARAM_BROWSE_PUBLICATION_TYPE);
    }
}
