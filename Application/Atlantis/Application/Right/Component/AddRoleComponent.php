<?php
namespace Ehb\Application\Atlantis\Application\Right\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Atlantis\Application\Right\Form\RoleForm;
use Ehb\Application\Atlantis\Application\Right\Manager;

class AddRoleComponent extends Manager
{

    public function run()
    {
        $right_id = Request :: get(self :: PARAM_RIGHT_ID);
        $application_id = Request :: get(self :: PARAM_APPLICATION_ID);

        $form = new RoleForm(
            $application_id,
            $right_id,
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_ADD_ROLE)));

        $html = array();

        $html[] = $this->render_header();
        $html[] = $form->toHtml();
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }
}
