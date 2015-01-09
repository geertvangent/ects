<?php
namespace Chamilo\Application\Atlantis\Application\Right\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Application\Atlantis\Application\Right\Manager;
use Chamilo\Application\Atlantis\Application\Right\Table\DataClass\Right;
use Chamilo\Application\Atlantis\Rights\Storage\DataManager;
use Chamilo\Application\Atlantis\Application\Right\Form\RightForm;

class EditorComponent extends Manager
{

    public function run()
    {
        SessionBreadcrumbs :: add(
            new Breadcrumb(
                $this->get_url(),
                Translation :: get(Utilities :: get_classname_from_namespace(self :: class_name()))));

        $right_id = Request :: get(self :: PARAM_RIGHT_ID);

        if (isset($right_id))
        {
            $right = DataManager :: retrieve(Right :: class_name(), (int) $right_id);

            if (! $this->get_user()->is_platform_admin())

            {
                $this->redirect('', true, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
            }

            $form = new RightForm(
                $right,
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_EDIT, self :: PARAM_RIGHT_ID => $right_id)));

            if ($form->validate())
            {
                $values = $form->exportValues();

                $right->set_name($values[Right :: PROPERTY_NAME]);
                $right->set_description($values[Right :: PROPERTY_DESCRIPTION]);
                $right->set_code($values[Right :: PROPERTY_CODE]);

                $success = $right->update();

                $parameters = array();
                $parameters[self :: PARAM_ACTION] = self :: ACTION_BROWSE;

                $this->redirect(
                    Translation :: get(
                        $success ? 'ObjectUpdated' : 'ObjectNotUpdated',
                        array('OBJECT' => Translation :: get('Right')),
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
                        array('OBJECT' => Translation :: get('Right')),
                        Utilities :: COMMON_LIBRARIES)));
        }
    }
}
