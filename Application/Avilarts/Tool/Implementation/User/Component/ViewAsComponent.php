<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User\Component;

use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Session\Session;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Avilarts\Tool\Implementation\User\Manager;

/**
 * Logs a teacher in/out of the student view.
 *
 * @author Tom Goethals
 */
class ViewAsComponent extends Manager
{

    public function run()
    {
        $view_as_user_id = Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_USERS);
        if (! isset($view_as_user_id))
        {
            // if the teacher is already logged in as another user, log him out
            // this time.

            Session :: unregister(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_VIEW_AS_ID);
            Session :: unregister(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_VIEW_AS_COURSE_ID);

            $this->redirect(
                Translation :: get('ViewAsOriginal'),
                false,
                array(
                    \Ehb\Application\Avilarts\Manager :: PARAM_TOOL => null,
                    \Ehb\Application\Avilarts\Manager :: PARAM_TOOL_ACTION => null,
                    \Ehb\Application\Avilarts\Manager :: PARAM_USERS => null));
        }
        else
        {
            if ($this->get_parent()->is_teacher())
            {
                Session :: register(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_VIEW_AS_ID, $view_as_user_id);
                Session :: register(
                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_VIEW_AS_COURSE_ID,
                    Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_COURSE));
                $this->redirect(
                    Translation :: get('ViewAsUser'),
                    false,
                    array(
                        \Ehb\Application\Avilarts\Manager :: PARAM_TOOL => null,
                        \Ehb\Application\Avilarts\Manager :: PARAM_TOOL_ACTION => null,
                        \Ehb\Application\Avilarts\Manager :: PARAM_USERS => null));
            }
            else
            {
                throw new NotAllowedException();
            }
        }
    }
}
