<?php
namespace Ehb\Application\Avilarts\Tool\Action\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Action\Manager;

/**
 * Toolcomponent to sent email of an alredy published publication.
 * Will only send a publication once!
 */
class PublicationMailerComponent extends Manager
{

    public function run()
    {
        if (Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID))
        {
            $publication_id = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
        }

        if (isset($publication_id))
        {

            $failure = false;

            $publication = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
                ContentObjectPublication :: class_name(),
                $publication_id);

            // currently: publications only sent once! Maybe this is not necessary...
            if ($publication->is_email_sent())
            {
                $message = htmlentities(Translation :: get('EmailAlreadySent'));
                $failure = true;
            }
            else
            {
                $message = htmlentities(Translation :: get('NotAllowed'));
                $failure = true;
            }

            $params = array();
            $params['tool_action'] = null;
            if (Request :: get('details') == 1)
            {
                $params[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID] = $publication_id;
                $params['tool_action'] = 'view';
            }

            $this->redirect($message, $failure, $params);
        }
        else
        {
            $html = array();

            $html[] = $this->render_header();
            $html[] = $this->display_error_message(Translation :: get('NoObjectsSelected'));
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
    }
}
