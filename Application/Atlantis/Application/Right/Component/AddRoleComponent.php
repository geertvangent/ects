<?php
namespace Chamilo\Application\Atlantis\Application\Right\Component;

use Chamilo\Libraries\Platform\Request;

class AddRoleComponent extends Manager
{

    public function run()
    {
        $right_id = Request :: get(self :: PARAM_RIGHT_ID);
        $application_id = Request :: get(self :: PARAM_APPLICATION_ID);
        
        $this->display_header();
        $form = new RoleForm(
            $application_id, 
            $right_id, 
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_ADD_ROLE)));
        $form->display();
        $this->display_footer();
    }
}
