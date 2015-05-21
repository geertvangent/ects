<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Note\Component;

use Ehb\Application\Avilarts\Tool\Implementation\Note\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

/**
 * $Id: note_viewer.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.note.component
 */
class ViewerComponent extends Manager implements DelegateComponent
{

    public function get_additional_parameters()
    {
        return array(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
