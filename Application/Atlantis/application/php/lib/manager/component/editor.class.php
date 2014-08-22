<?php
namespace application\atlantis\application;

use libraries\Breadcrumb;
use application\atlantis\SessionBreadcrumbs;
use libraries\Utilities;
use libraries\Translation;
use libraries\Request;

class EditorComponent extends Manager
{

    public function run()
    {
        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get(Utilities :: get_classname_from_namespace(self :: class_name()))));

        $application_id = Request :: get(self :: PARAM_APPLICATION_ID);

        if (isset($application_id))
        {
            $application = DataManager :: retrieve(Application :: class_name(), (int) $application_id);

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
