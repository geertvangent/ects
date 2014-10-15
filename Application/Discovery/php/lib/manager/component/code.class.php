<?php
namespace application\discovery;

use libraries\architecture\DelegateComponent;
use libraries\platform\Translation;
use libraries\platform\Request;

/**
 *
 * @author Hans De Bisschop
 * @package application.discovery
 */
class CodeComponent extends Manager implements DelegateComponent
{

    public function run()
    {
        $module_id = Request :: get(Manager :: PARAM_MODULE_ID);
        $official_code = Request :: get(Manager :: PARAM_OFFICIAL_CODE);
        
        $user = \core\user\DataManager :: retrieve_user_by_official_code($official_code);
        $module_instance = \application\discovery\instance\DataManager :: retrieve_by_id(
            \application\discovery\instance\Instance :: class_name(), 
            (int) $module_id);
        
        if ($user instanceof \core\user\User && $module_instance instanceof \application\discovery\instance\Instance &&
             $module_instance->get_content_type() == \application\discovery\instance\Instance :: TYPE_USER)
        {
            $parameters = array();
            $parameters[self :: PARAM_APPLICATION] = self :: APPLICATION_NAME;
            $parameters[self :: PARAM_ACTION] = self :: ACTION_VIEW;
            $parameters[self :: PARAM_MODULE_ID] = $module_instance->get_id();
            $parameters[self :: PARAM_USER_ID] = $user->get_id();
            
            $this->redirect(null, false, $parameters);
        }
        else
        {
            throw new \Exception(Translation :: get('NoSuchUserOrModule'));
        }
    }
}
