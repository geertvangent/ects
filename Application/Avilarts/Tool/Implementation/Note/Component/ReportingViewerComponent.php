<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Note\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Ehb\Application\Avilarts\Tool\Implementation\Note\Manager;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;

class ReportingViewerComponent extends Manager implements DelegateComponent
{

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add(
            new Breadcrumb(
                $this->get_url(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_BROWSE)),
                Translation :: get('NoteToolBrowserComponent')));

        $breadcrumbtrail->add(
            new Breadcrumb(
                $this->get_url(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_VIEW,
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID => Request :: get(
                            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID))),
                Translation :: get('NoteToolViewerComponent')));
    }

    public function get_additional_parameters()
    {
        return array(
            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID,
            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_COMPLEX_ID,
            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_TEMPLATE_NAME);
    }
}
