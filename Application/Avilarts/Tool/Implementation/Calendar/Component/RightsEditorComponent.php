<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Calendar\Component;


use Ehb\Application\Avilarts\Tool\Implementation\Calendar\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

class RightsEditorComponent extends Manager implements DelegateComponent
{

    public function get_available_rights($location)
    {
        return \Ehb\Application\Avilarts\Rights\Rights :: get_available_rights($location);
    }

    public function get_additional_parameters()
    {
        array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
