<?php
namespace Ehb\Application\Atlantis\Application\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Application\Form\ApplicationForm;
use Ehb\Application\Atlantis\Application\Manager;
use Ehb\Application\Atlantis\Application\Storage\DataClass\Application;
use Ehb\Application\Atlantis\Application\Storage\DataManager;
use Ehb\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Architecture\ClassnameUtilities;

class EditorComponent extends Manager
{

    public function run()
    {
        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get(ClassnameUtilities :: getInstance()->getClassnameFromNamespace(self :: class_name()))));

        $application_id = Request :: get(self :: PARAM_APPLICATION_ID);

        if (isset($application_id))
        {
            $application = DataManager :: retrieve_by_id(Application :: class_name(), (int) $application_id);

            if (! $this->get_user()->is_platform_admin())
            {
                $this->redirect('', true, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
            }

            $form = new ApplicationForm(
                $application,
                $this->get_url(
                    array(self :: PARAM_ACTION => self :: ACTION_EDIT, self :: PARAM_APPLICATION_ID => $application_id)));

            if ($form->validate())
            {
                $values = $form->exportValues();

                $application->set_name($values[Application :: PROPERTY_NAME]);
                $application->set_description($values[Application :: PROPERTY_DESCRIPTION]);
                $application->set_url($values[Application :: PROPERTY_URL]);
                $application->set_code($values[Application :: PROPERTY_CODE]);

                $success = $application->update();

                $parameters = array();
                $parameters[self :: PARAM_ACTION] = self :: ACTION_BROWSE;

                $this->redirect(
                    Translation :: get(
                        $success ? 'ObjectUpdated' : 'ObjectNotUpdated',
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
        else
        {
            $this->display_error_page(
                htmlentities(
                    Translation :: get(
                        'NoObjectSelected',
                        array('OBJECT' => Translation :: get('Application')),
                        Utilities :: COMMON_LIBRARIES)));
        }
    }
}
