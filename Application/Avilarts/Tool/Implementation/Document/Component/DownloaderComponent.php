<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Document\Component;

use Ehb\Application\Avilarts\Rights\WeblcmsRights;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Implementation\Document\Manager;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * $Id: document_downloader.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.tool.document.component
 */
class DownloaderComponent extends Manager
{

    public function run()
    {
        $publication_id = Request :: get(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID);
        $publication = \Chamilo\Application\Weblcms\Storage\DataManager :: retrieve_by_id(
            ContentObjectPublication :: class_name(), 
            $publication_id);
        
        if (! $this->is_allowed(WeblcmsRights :: VIEW_RIGHT, $publication))
        {
            $this->redirect(
                Translation :: get("NotAllowed", null, Utilities :: COMMON_LIBRARIES), 
                true, 
                array(), 
                array(
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_ACTION, 
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_PUBLICATION_ID));
        }
        
        $document = $publication->get_content_object();
        $document->send_as_download();
        return '';
    }
}
