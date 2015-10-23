<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Home\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Home\Manager;

class ContentObjectUpdaterComponent extends Manager
{

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
