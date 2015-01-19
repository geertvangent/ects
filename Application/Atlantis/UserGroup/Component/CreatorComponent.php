<?php
namespace Chamilo\Application\Atlantis\UserGroup\Component;

use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Application\Atlantis\UserGroup\Manager;

class CreatorComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->redirect('', true, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
        }

        $application = new \Chamilo\Application\Atlantis\Application\Storage\DataClass\Application();

        $form = new \Chamilo\Application\Atlantis\Application\Form\ApplicationForm(
            $application,
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE)));

        if ($form->validate())
        {
            $values = $form->exportValues();

            $application->set_name($values[\Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_NAME]);
            $application->set_description(
                $values[\Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_DESCRIPTION]);
            $application->set_url($values[\Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_URL]);

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
