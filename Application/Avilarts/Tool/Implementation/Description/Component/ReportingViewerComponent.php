<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Description\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Avilarts\Tool\Implementation\Description\Manager;

class ReportingViewerComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(
            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID,
            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_COMPLEX_ID,
            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_TEMPLATE_NAME);
    }
}
