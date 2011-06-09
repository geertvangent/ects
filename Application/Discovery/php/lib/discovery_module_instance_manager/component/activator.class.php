<?php
namespace application\discovery;

use common\libraries\Request;
use common\libraries\Translation;
use common\libraries\Utilities;

class DiscoveryModuleInstanceManagerActivatorComponent extends DiscoveryModuleInstanceManager
{

    function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }

        $ids = Request :: get(DiscoveryModuleInstanceManager :: PARAM_INSTANCE);
        $failures = 0;

        if (! empty($ids))
        {
            if (! is_array($ids))
            {
                $ids = array($ids);
            }

            foreach ($ids as $id)
            {
                $discovery_module_instance = DiscoveryDataManager :: get_instance()->retrieve_discovery_module_instance($id);
                $discovery_module_instance->activate();

                if (! $discovery_module_instance->update())
                {
                    $failures ++;
                }
            }

            if ($failures)
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectNotActivated';
                    $parameter = array('OBJECT' => Translation :: get('DiscoveryModuleInstance'));
                }
                else
                {
                    $message = 'ObjectsNotActivated';
                    $parameter = array('OBJECTS' => Translation :: get('VideosConferencing'));
                }
            }
            else
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectActivated';
                    $parameter = array('OBJECT' => Translation :: get('DiscoveryModuleInstance'));
                }
                else
                {
                    $message = 'ObjectsActivated';
                    $parameter = array('OBJECTS' => Translation :: get('VideosConferencing'));
                }
            }

            $this->redirect(Translation :: get($message, $parameter, Utilities :: COMMON_LIBRARIES), ($failures ? true : false), array(
                    DiscoveryModuleInstanceManager :: PARAM_INSTANCE_ACTION => DiscoveryModuleInstanceManager :: ACTION_BROWSE_INSTANCES));
        }
        else
        {
            $this->display_error_page(htmlentities(Translation :: get('NoObjectSelected', array(
                    'OBJECT' => Translation :: get('ExternalRepository')), Utilities :: COMMON_LIBRARIES)));
        }
    }
}
?>