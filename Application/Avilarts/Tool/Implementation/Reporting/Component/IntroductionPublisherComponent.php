<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Reporting\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Avilarts\Tool\Implementation\Reporting\Manager;

class IntroductionPublisherComponent extends Manager
{

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add(
            new Breadcrumb(
                $this->get_url(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_BROWSE)),
                Translation :: get('ReportingToolBrowserComponent')));
    }
}
