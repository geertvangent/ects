<?php
namespace application\discovery;

use common\libraries\Request;
use common\libraries\Translation;
use common\libraries\Utilities;

class ModuleInstanceManagerActivatorComponent extends ModuleInstanceManager
{

    function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }
        
        $ids = Request :: get(DiscoveryManager :: PARAM_MODULE_ID);
        $failures = 0;
        
        if (! empty($ids))
        {
            if (! is_array($ids))
            {
                $ids = array($ids);
            }
            
            foreach ($ids as $id)
            {
                $module_instance = DiscoveryDataManager :: get_instance()->retrieve_module_instance($id);
                $module_instance->activate();
                
                if (! $module_instance->update())
                {
                    $failures ++;
                }
            }
            
            if ($failures)
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectNotActivated';
                    $parameter = array('OBJECT' => Translation :: get('ModuleInstance'));
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
                    $parameter = array('OBJECT' => Translation :: get('ModuleInstance'));
                }
                else
                {
                    $message = 'ObjectsActivated';
                    $parameter = array('OBJECTS' => Translation :: get('VideosConferencing'));
                }
            }
            
            $this->redirect(Translation :: get($message, $parameter, Utilities :: COMMON_LIBRARIES), ($failures ? true : false), array(
                    ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_BROWSE_INSTANCES, 
                    self :: PARAM_CONTENT_TYPE => $module_instance->get_content_type()));
        }
        else
        {
            $this->display_error_page(htmlentities(Translation :: get('NoObjectSelected', array(
                    'OBJECT' => Translation :: get('ExternalRepository')), Utilities :: COMMON_LIBRARIES)));
        }
    }
}
?>