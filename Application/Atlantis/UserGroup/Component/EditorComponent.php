<?php
namespace Ehb\Application\Atlantis\UserGroup\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\UserGroup\Manager;
use Ehb\Application\Atlantis\UserGroup\Storage\DataManager;

class EditorComponent extends Manager
{

    public function run()
    {
        $application_id = Request::get(self::PARAM_APPLICATION_ID);
        
        if (isset($application_id))
        {
            $application = DataManager::retrieve_by_id(
                \Ehb\Application\Atlantis\Application\Storage\DataClass\Application::class_name(), 
                (int) $application_id);
            
            if (! $this->get_user()->is_platform_admin())
            {
                $this->redirect('', true, array(self::PARAM_ACTION => self::ACTION_BROWSE));
            }
            
            $form = new \Ehb\Application\Atlantis\Application\Form\ApplicationForm(
                $application, 
                $this->get_url(
                    array(self::PARAM_ACTION => self::ACTION_EDIT, self::PARAM_APPLICATION_ID => $application_id)));
            
            if ($form->validate())
            {
                $values = $form->exportValues();
                
                $application->set_name(
                    $values[\Ehb\Application\Atlantis\Application\Storage\DataClass\Application::PROPERTY_NAME]);
                $application->set_description(
                    $values[\Ehb\Application\Atlantis\Application\Storage\DataClass\Application::PROPERTY_DESCRIPTION]);
                $application->set_url(
                    $values[\Ehb\Application\Atlantis\Application\Storage\DataClass\Application::PROPERTY_URL]);
                
                $success = $application->update();
                
                $parameters = array();
                $parameters[self::PARAM_ACTION] = self::ACTION_BROWSE;
                
                $this->redirect(
                    Translation::get(
                        $success ? 'ObjectUpdated' : 'ObjectNotUpdated', 
                        array('OBJECT' => Translation::get('Application')), 
                        Utilities::COMMON_LIBRARIES), 
                    ($success ? false : true), 
                    $parameters);
            }
            else
            {
                $html = array();
                
                $html[] = $this->render_header();
                $html[] = $form->toHtml();
                $html[] = $this->render_footer();
                
                return implode(PHP_EOL, $html);
            }
        }
        else
        {
            return $this->display_error_page(
                htmlentities(
                    Translation::get(
                        'NoObjectSelected', 
                        array('OBJECT' => Translation::get('Application')), 
                        Utilities::COMMON_LIBRARIES)));
        }
    }
}
