<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Announcement\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Announcement\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class CategoryMoverComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
