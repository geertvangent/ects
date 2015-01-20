<?php
namespace Ehb\Application\Atlantis\Role\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Ehb\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Atlantis\Role\Manager;
use Ehb\Application\Atlantis\Role\DataClass\Role;
use Ehb\Application\Atlantis\Role\Form\RoleForm;
use Ehb\Application\Atlantis\Role\DataManager;

class EditorComponent extends Manager
{

    public function run()
    {
        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get(Utilities :: get_classname_from_namespace(self :: class_name()))));

        $role_id = Request :: get(self :: PARAM_ROLE_ID);

        if (isset($role_id))
        {
            $role = DataManager :: retrieve(Role :: class_name(), (int) $role_id);

            if (! $this->get_user()->is_platform_admin())

            {
                $this->redirect('', true, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
            }

            $form = new RoleForm(
                $role,
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_EDIT, self :: PARAM_ROLE_ID => $role_id)));

            if ($form->validate())
            {
                $values = $form->exportValues();

                $role->set_name($values[Role :: PROPERTY_NAME]);
                $role->set_description($values[Role :: PROPERTY_DESCRIPTION]);

                $success = $role->update();

                $parameters = array();
                $parameters[self :: PARAM_ACTION] = self :: ACTION_BROWSE;

                $this->redirect(
                    Translation :: get(
                        $success ? 'ObjectUpdated' : 'ObjectNotUpdated',
                        array('OBJECT' => Translation :: get('Role')),
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
                        array('OBJECT' => Translation :: get('Role')),
                        Utilities :: COMMON_LIBRARIES)));
        }
    }
}
