<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseSettings\Component;

use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Avilarts\Course\Interfaces\CourseSubManagerSupport;
use Ehb\Application\Avilarts\Tool\Implementation\CourseSettings\Manager;

/**
 * $Id: course_settings_updater.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.course_settings.component
 */
class UpdaterComponent extends Manager implements DelegateComponent, CourseSubManagerSupport
{

    public function run()
    {
        if (! $this->get_course()->is_course_admin($this->get_user()))
        {
            throw new NotAllowedException();
        }

        Request :: set_get(
            \Ehb\Application\Avilarts\Course\Manager :: PARAM_ACTION,
            \Ehb\Application\Avilarts\Course\Manager :: ACTION_QUICK_UPDATE);
        Request :: set_get(\Ehb\Application\Avilarts\Course\Manager :: PARAM_COURSE_ID, $this->get_course_id());

        $this->getRequest()->query->set(
            \Ehb\Application\Avilarts\Course\Manager :: PARAM_ACTION,
            \Ehb\Application\Avilarts\Course\Manager :: ACTION_QUICK_UPDATE);
        $this->getRequest()->query->set(
            \Ehb\Application\Avilarts\Course\Manager :: PARAM_COURSE_ID,
            $this->get_course_id());

        $factory = new ApplicationFactory(
            \Ehb\Application\Avilarts\Course\Manager :: context(),
            new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
        return $factory->run();
    }

    public function redirect_after_quick_create($succes, $message)
    {
        $this->redirect($message, ! $succes, array(), array(self :: PARAM_ACTION, self :: ACTION_UPDATE));
    }

    /**
     * Redirects the submanager to another component after a quick update
     *
     * @param boolean $succes
     * @param String $message
     */
    public function redirect_after_quick_update($succes, $message)
    {
        $this->redirect_after_quick_create($succes, $message);
    }
}
