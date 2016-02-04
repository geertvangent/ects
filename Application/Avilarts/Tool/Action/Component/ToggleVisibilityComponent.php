<?php
namespace Ehb\Application\Avilarts\Tool\Action\Component;


use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Action\Manager;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;

/**
 * $Id: toggle_visibility.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.component
 */
class ToggleVisibilityComponent extends Manager
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

        if (isset($publication_ids))
        {
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

                if ($this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT, $publication))
                {

                    if (! $this instanceof ToggleVisibilityComponent)
                    {
                        $publication->set_hidden($this->get_hidden());
                    }
                    else
                    {
                        $publication->toggle_visibility();
                    }

                    $publication->update();
                }
                else
                {
                    $message = htmlentities(Translation :: get('NotAllowed'));
                    $failures ++;
                }
            }
            if ($failures == 0)
            {
                if (count($publication_ids) > 1)
                {
                    $message = htmlentities(Translation :: get('ContentObjectPublicationsVisibilityChanged'));
                }
                else
                {
                    $message = htmlentities(Translation :: get('ContentObjectPublicationVisibilityChanged'));
                }
            }

            $params = array();
            $params['tool_action'] = null;
            if (Request :: get('details') == 1)
            {
                $params[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID] = $pid;
                $params['tool_action'] = 'view';
            }

            $this->redirect($message, $failures > 0, $params);
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
