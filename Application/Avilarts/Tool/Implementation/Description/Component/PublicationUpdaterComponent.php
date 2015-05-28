<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Description\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Description\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class PublicationUpdaterComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
