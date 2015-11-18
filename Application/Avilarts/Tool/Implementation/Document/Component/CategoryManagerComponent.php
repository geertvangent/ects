<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Document\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Document\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class CategoryManagerComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Chamilo\Configuration\Category\Manager :: PARAM_CATEGORY_ID);
    }
}
