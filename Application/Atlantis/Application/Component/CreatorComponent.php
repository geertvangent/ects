<?php
namespace Chamilo\Application\Atlantis\Application\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Application\Atlantis\Application\Manager;
use Chamilo\Application\Atlantis\Application\Storage\DataClass\Application;
use Chamilo\Application\Atlantis\Application\Form\ApplicationForm;

class CreatorComponent extends Manager
{

    public function run()
    {
        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get(Utilities :: get_classname_from_namespace(self :: class_name()))));

        if (! $this->get_user()->is_platform_admin())
        {
            $this->redirect('', true, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
        }

        $application = new Application();

        $form = new ApplicationForm($application, $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE)));

        if ($form->validate())
        {
            $values = $form->exportValues();

            $application->set_name($values[Application :: PROPERTY_NAME]);
            $application->set_description($values[Application :: PROPERTY_DESCRIPTION]);
            $application->set_url($values[Application :: PROPERTY_URL]);
            $application->set_code($values[Application :: PROPERTY_CODE]);

            $success = $application->create();

            $parameters = array();
            $parameters[self :: PARAM_ACTION] = self :: ACTION_BROWSE;

            $this->redirect(
                Translation :: get(
                    $success ? 'ObjectCreated' : 'ObjectNotCreated',
                    array('OBJECT' => Translation :: get('Application')),
                    Utilities :: COMMON_LIBRARIES),
                ($success ? false : true),
                $parameters);
        }
        else
        {
            $this->display_header();
            $form->display();
            $this->display_footer();
        }
    }
}
