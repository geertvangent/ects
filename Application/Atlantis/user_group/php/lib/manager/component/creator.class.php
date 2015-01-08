<?php
namespace application\atlantis\user_group;

use libraries\utilities\Utilities;
use libraries\platform\translation\Translation;

class CreatorComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->redirect('', true, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
        }
        
        $application = new \application\atlantis\application\Application();
        
        $form = new \application\atlantis\application\ApplicationForm(
            $application, 
            $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE)));
        
        if ($form->validate())
        {
            $values = $form->exportValues();
            
            $application->set_name($values[\application\atlantis\application\Application :: PROPERTY_NAME]);
            $application->set_description(
                $values[\application\atlantis\application\Application :: PROPERTY_DESCRIPTION]);
            $application->set_url($values[\application\atlantis\application\Application :: PROPERTY_URL]);
            
            $success = $application->create();
            
            $parameters = array();
            $parameters[self :: PARAM_ACTION] = self :: ACTION_BROWSE;
            
            $this->redirect(
                Translation :: get(
                    $success ? 'ObjectCreated' : 'ObjectNotCreated', 
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
}
