<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Document\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Avilarts\Tool\Implementation\Document\Manager;

class UpdaterComponent extends Manager
{

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add(
            new Breadcrumb(
                $this->get_url(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_BROWSE)),
                Translation :: get('DocumentToolBrowserComponent')));

        $breadcrumbtrail->add(
            new Breadcrumb(
                $this->get_url(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_VIEW,
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID => Request :: get(
                            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID))),
                Translation :: get('DocumentToolViewerComponent')));
    }

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
