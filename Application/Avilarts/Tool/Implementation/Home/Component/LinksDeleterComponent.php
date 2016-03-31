<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Home\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Implementation\Home\Manager;

class LinksDeleterComponent extends Manager
{

    public function run()
    {
        $pub_id = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
        
        $publication = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
            ContentObjectPublication :: class_name(), 
            $pub_id);
        
        $publication->set_show_on_homepage(0);
        $succes = $publication->update();
        
        $message = $succes ? 'PublicationRemovedFromHomepage' : 'PublicationNotRemovedFromHomepage';
        
        $this->redirect(Translation :: get($message), ! $succes, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
    }
}
