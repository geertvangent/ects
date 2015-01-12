<?php
namespace application\discovery\instance;

use libraries\platform\Request;
use libraries\platform\translation\Translation;
use libraries\utilities\Utilities;

class UpdaterComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }
        
        $instance_id = Request :: get(Manager :: PARAM_MODULE_ID);
        
        if (isset($instance_id))
        {
            $module_instance = DataManager :: retrieve_by_id(Instance :: class_name(), (int) $instance_id);
            
            $form = new InstanceForm(
                InstanceForm :: TYPE_EDIT, 
                $module_instance, 
                $this->get_url(array(Manager :: PARAM_MODULE_ID => $instance_id)));
            
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
