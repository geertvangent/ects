<?php
namespace application\atlantis\role;

use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\Request;

class CreatorComponent extends Manager
{

    function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->redirect('', true, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
        }
        
        $role = new Role();
        
        $form = new RoleForm($role, $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE)));
        
        if ($form->validate())
        {
            $values = $form->exportValues();
            
            $role->set_name($values[Role :: PROPERTY_NAME]);
            $role->set_description($values[Role :: PROPERTY_DESCRIPTION]);
            
            $success = $role->create();
            
            $parameters = array();
            $parameters[self :: PARAM_ACTION] = self :: ACTION_BROWSE;
            
            $this->redirect(Translation :: get($success ? 'ObjectCreated' : 'ObjectNotCreated', array(
                    'OBJECT' => Translation :: get('Role')), Utilities :: COMMON_LIBRARIES), ($success ? false : true), $parameters);
        }
        else
        {
            $this->display_header();
            $form->display();
            $this->display_footer();
        }
    
    }

}
?>