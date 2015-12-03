<?php
namespace Ehb\Application\Atlantis\UserGroup\Component;

use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\UserGroup\Manager;

class CreatorComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->redirect('', true, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
        }

        $application = new \Ehb\Application\Atlantis\Application\Storage\DataClass\Application();

        $form = new \Ehb\Application\Atlantis\Application\Form\ApplicationForm(
            $application,
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE)));

        if ($form->validate())
        {
            $values = $form->exportValues();

            $application->set_name(
                $values[\Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_NAME]);
            $application->set_description(
                $values[\Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_DESCRIPTION]);
            $application->set_url(
                $values[\Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_URL]);

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
            $html = array();

            $html[] = $this->render_header();
            $html[] = $form->toHtml();
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
    }
}
