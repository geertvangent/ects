<?php
namespace Chamilo\Application\Atlantis\Application\Right\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Application\Atlantis\SessionBreadcrumbs;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Application\Atlantis\Application\Right\Manager;
use Chamilo\Application\Atlantis\Application\Right\Table\DataClass\Right;
use Chamilo\Application\Atlantis\Application\Right\Form\RightForm;

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

        $right = new Right();
        $right->set_application_id(
            $this->get_parameter(\Chamilo\Application\Atlantis\Application\Manager :: PARAM_APPLICATION_ID));

        $form = new RightForm($right, $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE)));

        if ($form->validate())
        {
            $values = $form->exportValues();

            $right->set_name($values[Right :: PROPERTY_NAME]);
            $right->set_description($values[Right :: PROPERTY_DESCRIPTION]);
            $right->set_code($values[Right :: PROPERTY_CODE]);

            $success = $right->create();

            $parameters = array();
            $parameters[self :: PARAM_ACTION] = self :: ACTION_BROWSE;

            $this->redirect(
                Translation :: get(
                    $success ? 'ObjectCreated' : 'ObjectNotCreated',
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
}
