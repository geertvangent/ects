<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User\Component;

use Ehb\Application\Avilarts\Tool\Implementation\User\Manager;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Session\Session;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;

/**
 * Logs a teacher in/out of the student view.
 *
 * @author Tom Goethals
 */
class ViewAsComponent extends Manager
{

    public function run()
    {
        $view_as_user_id = Request :: get(\Chamilo\Application\Weblcms\Manager :: PARAM_USERS);
        if (! isset($view_as_user_id))
        {
            // if the teacher is already logged in as another user, log him out
            // this time.

            Session :: unregister(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_VIEW_AS_ID);
            Session :: unregister(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_VIEW_AS_COURSE_ID);

            $this->redirect(
                Translation :: get('ViewAsOriginal'),
                false,
                array(
                    \Chamilo\Application\Weblcms\Manager :: PARAM_TOOL => null,
                    \Chamilo\Application\Weblcms\Manager :: PARAM_TOOL_ACTION => null,
                    \Chamilo\Application\Weblcms\Manager :: PARAM_USERS => null));
        }
        else
        {
            if ($this->get_parent()->is_teacher())
            {
                Session :: register(\Chamilo\Application\Weblcms\Tool\Manager :: PARAM_VIEW_AS_ID, $view_as_user_id);
                Session :: register(
                    \Chamilo\Application\Weblcms\Tool\Manager :: PARAM_VIEW_AS_COURSE_ID,
                    Request :: get(\Chamilo\Application\Weblcms\Manager :: PARAM_COURSE));
                $this->redirect(
                    Translation :: get('ViewAsUser'),
                    false,
                    array(
                        \Chamilo\Application\Weblcms\Manager :: PARAM_TOOL => null,
                        \Chamilo\Application\Weblcms\Manager :: PARAM_TOOL_ACTION => null,
                        \Chamilo\Application\Weblcms\Manager :: PARAM_USERS => null));
            }
            else
            {
                throw new NotAllowedException();
            }
        }
    }
}
