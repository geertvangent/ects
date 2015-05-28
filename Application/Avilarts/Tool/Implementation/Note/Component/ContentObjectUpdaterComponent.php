<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Note\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Note\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class ContentObjectUpdaterComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
