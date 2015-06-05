<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Reporting\Component;


use Ehb\Application\Avilarts\Tool\Implementation\Reporting\Manager;
use Chamilo\Libraries\Architecture\Application\ApplicationFactory;
use Chamilo\Libraries\Architecture\Application\ApplicationConfiguration;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Platform\Session\Request;
use Exception;

/**
 * $Id: reporting_viewer.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.reporting.component
 */

/**
 *
 * @author Michael Kyndt
 */
class ViewerComponent extends Manager implements DelegateComponent
{

    public function run()
    {
        if (! $this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT))
        {
            throw new Exception('not-allowed');
        }

        $template_id = Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_TEMPLATE_ID);

        if (! isset($template_id))
        {
            $factory = new ApplicationFactory(
                \Chamilo\Core\Reporting\Viewer\Manager :: context(),
                new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
            $component = $factory->getComponent();
            $component->set_template_by_name(
                \Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Template\CourseStudentTrackerTemplate :: class_name());
            return $component->run();
        }
        else
        {
            if ($view = Request :: get(\Chamilo\Core\Reporting\Viewer\Manager :: PARAM_VIEW))
            {
                $this->set_parameter(\Chamilo\Core\Reporting\Viewer\Manager :: PARAM_VIEW, $view);
            }

            $this->set_parameter(\Ehb\Application\Avilarts\Manager :: PARAM_TEMPLATE_ID, $template_id);

            $factory = new ApplicationFactory(
                \Chamilo\Core\Reporting\Viewer\Manager :: context(),
                new ApplicationConfiguration($this->getRequest(), $this->get_user(), $this));
            $component = $factory->getComponent();
            $component->set_template_by_name($template_id);
            return $component->run();
        }
    }
}
