<?php
namespace Ehb\Application\Discovery\Instance\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Discovery\Instance\Form\InstanceForm;
use Ehb\Application\Discovery\Instance\Manager;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;
use Ehb\Application\Discovery\Instance\Storage\DataManager;

class UpdaterComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }
        
        $instance_id = Request::get(Manager::PARAM_MODULE_ID);
        
        if (isset($instance_id))
        {
            $module_instance = DataManager::retrieve_by_id(Instance::class_name(), (int) $instance_id);
            
            $form = new InstanceForm(
                InstanceForm::TYPE_EDIT, 
                $module_instance, 
                $this->get_url(array(Manager::PARAM_MODULE_ID => $instance_id)));
            
            if ($form->validate())
            {
                $success = $form->update_instance();
                $this->redirect(
                    Translation::get(
                        $success ? 'ObjectUpdated' : 'ObjectNotUpdated', 
                        array('OBJECT' => Translation::get('Instance')), 
                        Utilities::COMMON_LIBRARIES), 
                    ($success ? false : true), 
                    array(self::PARAM_ACTION => self::ACTION_BROWSE_INSTANCES));
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
                Translation::get(
                    'NoObjectSelected', 
                    array('OBJECT' => Translation::get('Instance')), 
                    Utilities::COMMON_LIBRARIES));
        }
    }
}
