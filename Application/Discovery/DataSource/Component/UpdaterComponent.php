<?php
namespace Ehb\Application\Discovery\DataSource\Component;

use Ehb\Application\Discovery\DataSource\DataClass\Instance;
use Ehb\Application\Discovery\DataSource\DataManager;
use Ehb\Application\Discovery\DataSource\Form\InstanceForm;
use Ehb\Application\Discovery\DataSource\Manager;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;

class UpdaterComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }
        
        $instance_id = Request :: get(Manager :: PARAM_DATA_SOURCE_ID);
        
        if (isset($instance_id))
        {
            $module_instance = DataManager :: retrieve_by_id(Instance :: class_name(), (int) $instance_id);
            
            $form = new InstanceForm(
                InstanceForm :: TYPE_EDIT, 
                $module_instance, 
                $this->get_url(array(Manager :: PARAM_DATA_SOURCE_ID => $instance_id)));
            
            if ($form->validate())
            {
                $success = $form->update_instance();
                $this->redirect(
                    Translation :: get(
                        $success ? 'ObjectUpdated' : 'ObjectNotUpdated', 
                        array('OBJECT' => Translation :: get('Instance')), 
                        Utilities :: COMMON_LIBRARIES), 
                    ($success ? false : true), 
                    array(self :: PARAM_ACTION => self :: ACTION_BROWSE_INSTANCES));
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
            $this->display_header();
            $this->display_error_message(
                Translation :: get(
                    'NoObjectSelected', 
                    array('OBJECT' => Translation :: get('Instance')), 
                    Utilities :: COMMON_LIBRARIES));
            $this->display_footer();
        }
    }
}
