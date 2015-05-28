<?php
namespace Ehb\Application\Avilarts\Component;

use Ehb\Application\Avilarts\Course\Interfaces\CourseSubManagerSupport;
use Ehb\Application\Avilarts\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;

/**
 * This class represents a component that runs the course type submanager
 *
 * @package \application\weblcms\course
 * @author Sven Vanpoucke - Hogeschool Gent - Refactoring
 */
class CourseManagerComponent extends Manager implements DelegateComponent, CourseSubManagerSupport
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $factory = new ApplicationFactory(
            $this->getRequest(),
            \Chamilo\Application\Weblcms\Course\Manager :: context(),
            $this->get_user(),
            $this);
        return $factory->run();
    }

    /**
     * Redirects the submanager to another component after a quick create
     *
     * @param $succes boolean
     * @param $message String
     */
    public function redirect_after_quick_create($succes, $message)
    {
        $this->redirect(
            $message,
            ! $succes,
            array(),
            array(self :: PARAM_ACTION, \Chamilo\Application\Weblcms\Course\Manager :: PARAM_ACTION));
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
