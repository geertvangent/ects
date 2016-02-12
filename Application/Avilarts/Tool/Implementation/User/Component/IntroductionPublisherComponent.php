<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User\Component;

use Ehb\Application\Avilarts\Tool\Implementation\User\Manager;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;

class IntroductionPublisherComponent extends Manager
{

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add(
            new Breadcrumb(
                $this->get_url(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => self :: ACTION_UNSUBSCRIBE_BROWSER,
                        self :: PARAM_TAB => Request :: get(self :: PARAM_TAB))),
                Translation :: get('UserToolUnsubscribeUserBrowserComponent')));
    }
}
