<?php
namespace Ehb\Application\Avilarts\Tool\Action\Component;

use Ehb\Application\Avilarts\Rights\WeblcmsRights;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Action\Manager;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;

/**
 * $Id: move.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.tool.component
 */
class MoverComponent extends Manager
{

    /**
     * Executes this controller
     * 
     * @throws \libraries\architecture\exceptions\NotAllowedException
     */
    public function run()
    {
        $publication_id = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
        $publication = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
            ContentObjectPublication :: class_name(), 
            $publication_id);
        
        if (! $this->is_allowed(WeblcmsRights :: EDIT_RIGHT, $publication))
        {
            throw new NotAllowedException();
        }
        
        $move = $this->get_parent()->get_move_direction();
        
        if ($publication->move($move))
        {
            $message = htmlentities(Translation :: get('ContentObjectPublicationMoved'));
        }
        
        $this->redirect(
            $message, 
            false, 
            array(
                \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => null, 
                \Ehb\Application\Avilarts\Tool\Manager :: PARAM_BROWSER_TYPE => Request :: get(
                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_BROWSER_TYPE), 
                \Ehb\Application\Avilarts\Tool\Manager :: PARAM_BROWSE_PUBLICATION_TYPE => Request :: get(
                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_BROWSE_PUBLICATION_TYPE)));
    }
}
