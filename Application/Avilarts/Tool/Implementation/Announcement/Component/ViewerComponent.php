<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Announcement\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Announcement\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class ViewerComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
