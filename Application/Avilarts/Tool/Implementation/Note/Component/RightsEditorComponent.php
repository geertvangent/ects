<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Note\Component;

use Ehb\Application\Avilarts\Rights\WeblcmsRights;
use Ehb\Application\Avilarts\Tool\Implementation\Note\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class RightsEditorComponent extends Manager implements DelegateComponent
{

    public function get_available_rights($location)
    {
        return WeblcmsRights :: get_available_rights($location);
    }

    public function get_additional_parameters()
    {
        array(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
