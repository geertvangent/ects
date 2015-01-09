<?php
namespace Chamilo\Application\Atlantis\Role\Entity\Component;

use Chamilo\Libraries\Platform\Request;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation\Translation;

class DeleterComponent extends Manager
{

    public function run()
    {
        $ids = Request :: get(self :: PARAM_ROLE_ENTITY_ID);
        $context_id = Request :: get(\Chamilo\Application\Atlantis\Context\Manager :: PARAM_CONTEXT_ID);
        $role_id = Request :: get(\Chamilo\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID);
        
        $failures = 0;
        
        if (! empty($ids))
        {
            if (! is_array($ids))
            {
                $ids = array($ids);
            }
            
            foreach ($ids as $id)
            {
                $role_entity = DataManager :: retrieve(RoleEntity :: class_name(), (int) $id);
                
                if (! \Chamilo\Application\Atlantis\Rights\Rights :: get_instance()->access_is_allowed())
                {
                    $failures ++;
                }
                else
                {
                    if (! $role_entity->delete())
                    {
                        $failures ++;
                    }
                    else
                    {
                        $role_entity->track($this->get_user_id(), RoleEntityTracker :: ACTION_TYPE_DELETE);
                    }
                }
            }
            
            if ($failures)
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectNotDeleted';
                    $parameter = array('OBJECT' => Translation :: get('RoleEntity'));
                }
                elseif (count($ids) > $failures)
                {
                    $message = 'SomeObjectsNotDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('RoleEntities'));
                }
                else
                {
                    $message = 'ObjectsNotDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('RoleEntities'));
                }
            }
            else
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectDeleted';
                    $parameter = array('OBJECT' => Translation :: get('RoleEntity'));
                }
                else
                {
                    $message = 'ObjectsDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('RoleEntities'));
                }
            }
            
            $this->redirect(
                Translation :: get($message, $parameter, Utilities :: COMMON_LIBRARIES), 
                ($failures ? true : false), 
                array(
                    Manager :: PARAM_ACTION => Manager :: ACTION_BROWSE, 
                    \Chamilo\Application\Atlantis\Role\Manager :: PARAM_ROLE_ID => $role_id, 
                    \Chamilo\Application\Atlantis\Context\Manager :: PARAM_CONTEXT_ID => $context_id));
        }
        else
        {
            $this->display_error_page(
                htmlentities(
                    Translation :: get(
                        'NoObjectSelected', 
                        array('OBJECT' => Translation :: get('Right')), 
                        Utilities :: COMMON_LIBRARIES)));
        }
    }
}
