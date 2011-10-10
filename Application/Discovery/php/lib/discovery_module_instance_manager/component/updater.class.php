<?php
namespace application\discovery;

use common\libraries\Request;
use common\libraries\Translation;
use common\libraries\Utilities;

require_once dirname(__FILE__) . '/../forms/module_instance_form.class.php';

class ModuleInstanceManagerUpdaterComponent extends ModuleInstanceManager
{

    function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }

        $instance_id = Request :: get(ModuleInstanceManager :: PARAM_INSTANCE);

        if(isset($instance_id))
        {
            $module_instance = DiscoveryDataManager::get_instance()->retrieve_module_instance($instance_id);
            $form = new ModuleInstanceForm(ModuleInstanceForm :: TYPE_EDIT, $module_instance, $this->get_url(array(ModuleInstanceManager :: PARAM_INSTANCE => $instance_id)));

            if ($form->validate())
            {
                $success = $form->update_module_instance();
                $this->redirect(Translation :: get($success ? 'ObjectUpdated' : 'ObjectNotUpdated', array('OBJECT' => Translation :: get('ModuleInstance')), Utilities :: COMMON_LIBRARIES), ($success ? false : true), array(ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_BROWSE_INSTANCES));
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
                $this->display_error_message(Translation :: get('NoObjectSelected', array('OBJECT' => Translation :: get('ModuleInstance')), Utilities :: COMMON_LIBRARIES));
                $this->display_footer();
        }
    }
}
?>