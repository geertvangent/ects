<?php
namespace application\atlantis\context;

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
        
        $application = new Application();
        
        $form = new ApplicationForm($application, $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_CREATE)));
        
        if ($form->validate())
        {
            $values = $form->exportValues();
            
            $application->set_name($values[Application :: PROPERTY_NAME]);
            $application->set_description($values[Application :: PROPERTY_DESCRIPTION]);
            $application->set_url($values[Application :: PROPERTY_URL]);
            
            $success = $application->create();
            
            $parameters = array();
            $parameters[self :: PARAM_ACTION] = self :: ACTION_BROWSE;
            
            $this->redirect(Translation :: get($success ? 'ObjectCreated' : 'ObjectNotCreated', array(
                    'OBJECT' => Translation :: get('Application')), Utilities :: COMMON_LIBRARIES), ($success ? false : true), $parameters);
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