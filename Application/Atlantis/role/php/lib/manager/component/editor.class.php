<?php
namespace application\atlantis\role;

use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\Request;

class EditorComponent extends Manager
{

    public function run()
    {
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
