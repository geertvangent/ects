<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Announcement\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Announcement\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class ReportingViewerComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(
            \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID,
            \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_COMPLEX_ID,
            \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_TEMPLATE_NAME);
    }
}
