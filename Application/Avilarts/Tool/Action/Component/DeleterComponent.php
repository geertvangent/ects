<?php
namespace Ehb\Application\Avilarts\Tool\Action\Component;

use Ehb\Application\Avilarts\Rights\WeblcmsRights;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Action\Manager;
use Chamilo\Core\Repository\ContentObject\Introduction\Storage\DataClass\Introduction;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;

/**
 * $Id: delete.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.tool.component
 */
class DeleterComponent extends Manager
{

    public function run()
    {
        if (Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID))
        {
            $publication_ids = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
        }
        else
        {
            $publication_ids = $_POST[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID];
        }
        
        if (! is_array($publication_ids))
        {
            $publication_ids = array($publication_ids);
        }
        
        $failures = 0;
        
        foreach ($publication_ids as $pid)
        {
            $publication = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
                ContentObjectPublication :: class_name(), 
                $pid);
            
            $content_object = $publication->get_content_object();
            
            if ($content_object->get_type() == Introduction :: class_name())
            {
                $publication->ignore_display_order();
            }
            
            if ($this->is_allowed(WeblcmsRights :: DELETE_RIGHT, $publication))
            {
                $publication->delete();
            }
            else
            {
                $failures ++;
            }
        }
        if ($failures == 0)
        {
            if (count($publication_ids) > 1)
            {
                $message = htmlentities(Translation :: get('ContentObjectPublicationsDeleted'));
            }
            else
            {
                $message = htmlentities(Translation :: get('ContentObjectPublicationDeleted'));
            }
        }
        else
        {
            $message = htmlentities(Translation :: get('ContentObjectPublicationsNotDeleted'));
        }
        
        $this->redirect(
            $message, 
            $failures > 0, 
            array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID => null, 'tool_action' => null));
    }
}
