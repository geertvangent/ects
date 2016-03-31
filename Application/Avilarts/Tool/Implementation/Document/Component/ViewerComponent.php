<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Document\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Ehb\Application\Avilarts\Tool\Implementation\Document\Manager;

/**
 * $Id: document_viewer.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.document.component
 */
class ViewerComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
