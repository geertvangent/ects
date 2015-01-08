<?php
namespace application\atlantis\role;

use libraries\format\Breadcrumb;
use application\atlantis\SessionBreadcrumbs;
use libraries\utilities\Utilities;
use libraries\platform\translation\Translation;

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
            
            $this->redirect(
                Translation :: get(
                    $success ? 'ObjectCreated' : 'ObjectNotCreated', 
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
}
