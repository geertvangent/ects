<?php
namespace Chamilo\Application\Atlantis\UserGroup\Component;

use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Platform\Session\Request;

class EditorComponent extends Manager
{

    public function run()
    {
        $application_id = Request :: get(self :: PARAM_APPLICATION_ID);
        
        if (isset($application_id))
        {
            $application = DataManager :: retrieve(
                \Chamilo\Application\Atlantis\Application\Application :: class_name(), 
                (int) $application_id);
            
            if (! $this->get_user()->is_platform_admin())
            {
                $this->redirect('', true, array(self :: PARAM_ACTION => self :: ACTION_BROWSE));
            }
            
            $form = new \Chamilo\Application\Atlantis\Application\ApplicationForm(
                $application, 
                $this->get_url(
                    array(self :: PARAM_ACTION => self :: ACTION_EDIT, self :: PARAM_APPLICATION_ID => $application_id)));
            
            if ($form->validate())
            {
                $values = $form->exportValues();
                
                $application->set_name($values[\Chamilo\Application\Atlantis\Application\Application :: PROPERTY_NAME]);
                $application->set_description(
                    $values[\Chamilo\Application\Atlantis\Application\Application :: PROPERTY_DESCRIPTION]);
                $application->set_url($values[\Chamilo\Application\Atlantis\Application\Application :: PROPERTY_URL]);
                
                $success = $application->update();
                
                $parameters = array();
                $parameters[self :: PARAM_ACTION] = self :: ACTION_BROWSE;
                
                $this->redirect(
                    Translation :: get(
                        $success ? 'ObjectUpdated' : 'ObjectNotUpdated', 
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
        else
        {
            $this->display_error_page(
                htmlentities(
                    Translation :: get(
                        'NoObjectSelected', 
                        array('OBJECT' => Translation :: get('Application')), 
                        Utilities :: COMMON_LIBRARIES)));
        }
    }
}
