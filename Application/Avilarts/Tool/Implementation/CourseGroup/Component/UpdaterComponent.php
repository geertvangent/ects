<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Component;

use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Manager;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;

class UpdaterComponent extends Manager
{

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add(
            new Breadcrumb(
                $this->get_url(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_BROWSE)),
                Translation :: get('CourseGroupToolBrowserComponent')));
        $breadcrumbtrail->add(
            new Breadcrumb(
                $this->get_url(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_VIEW,
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID => Request :: get(
                            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID))),
                Translation :: get('CourseGroupToolBrowserComponent')));
    }

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
