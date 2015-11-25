<?php
namespace Ehb\Application\Discovery\Component;

use Ehb\Application\Discovery\Manager;
use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;

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

        $user = \Chamilo\Core\User\Storage\DataManager :: retrieve_user_by_official_code($official_code);
        $module_instance = \Ehb\Application\Discovery\Instance\Storage\DataManager :: retrieve_by_id(
            \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: class_name(),
            (int) $module_id);

        if ($user instanceof \Chamilo\Core\User\Storage\DataClass\User &&
             $module_instance instanceof \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance && $module_instance->get_content_type() ==
             \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance :: TYPE_USER)
        {
            $parameters = array();
            $parameters[self :: PARAM_APPLICATION] = self :: package();
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
