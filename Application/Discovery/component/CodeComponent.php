<?php
namespace Chamilo\Application\Discovery\Component;

use Chamilo\Libraries\Architecture\DelegateComponent;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Platform\Request;

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
        
        $user = \Chamilo\Core\User\DataManager :: retrieve_user_by_official_code($official_code);
        $module_instance = \Chamilo\Application\Discovery\Instance\DataManager :: retrieve_by_id(
            \Chamilo\Application\Discovery\Instance\Instance :: class_name(), 
            (int) $module_id);
        
        if ($user instanceof \Chamilo\Core\User\User && $module_instance instanceof \Chamilo\Application\Discovery\Instance\Instance &&
             $module_instance->get_content_type() == \Chamilo\Application\Discovery\Instance\Instance :: TYPE_USER)
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
            throw new \Chamilo\Exception(Translation :: get('NoSuchUserOrModule'));
        }
    }
}
