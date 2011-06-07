<?php
namespace application\discovery;

use common\libraries\Request;
use common\libraries\Translation;
use common\libraries\Utilities;

require_once dirname(__FILE__) . '/../forms/discovery_module_instance_form.class.php';

class DiscoveryModuleInstanceManagerUpdaterComponent extends DiscoveryModuleInstanceManager
{

    function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }

        $instance_id = Request :: get(DiscoveryModuleInstanceManager :: PARAM_INSTANCE);

        if(isset($instance_id))
        {
            $discovery_module_instance = $this->retrieve_discovery_module_instance($instance_id);
            $form = new DiscoveryModuleInstanceForm(DiscoveryModuleInstanceForm :: TYPE_EDIT, $discovery_module_instance, $this->get_url(array(DiscoveryModuleInstanceManager :: PARAM_INSTANCE => $instance_id)));

            if ($form->validate())
            {
                $success = $form->update_discovery_module_instance();
                $this->redirect(Translation :: get($success ? 'ObjectUpdated' : 'ObjectNotUpdated', array('OBJECT' => Translation :: get('DiscoveryModuleInstance')), Utilities :: COMMON_LIBRARIES), ($success ? false : true), array(DiscoveryModuleInstanceManager :: PARAM_INSTANCE_ACTION => DiscoveryModuleInstanceManager :: ACTION_BROWSE_INSTANCES));
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
                $this->display_error_message(Translation :: get('NoObjectSelected', array('OBJECT' => Translation :: get('DiscoveryModuleInstance')), Utilities :: COMMON_LIBRARIES));
                $this->display_footer();
        }
    }
}
?>