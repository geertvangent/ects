<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Link\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Link\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

/**
 * $Id: Link_viewer.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.Link.component
 */
class ViewerComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
