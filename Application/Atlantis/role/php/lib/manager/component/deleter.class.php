<?php
namespace application\atlantis\role;

use common\libraries\Request;
use common\libraries\Utilities;
use common\libraries\Translation;

class DeleterComponent extends Manager
{

    function run()
    {
        $ids = Request :: get(self :: PARAM_ROLE_ID);
        $failures = 0;
        
        if (! empty($ids))
        {
            if (! is_array($ids))
            {
                $ids = array($ids);
            }
            
            foreach ($ids as $id)
            {
                $role = DataManager :: retrieve(Role :: class_name(), (int) $id);
                
                if (! $this->get_user()->is_platform_admin())
                {
                    $failures ++;
                }
                else
                {
                    if (! $role->delete())
                    {
                        $failures ++;
                    }
                }
            }
            
            if ($failures)
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectNotDeleted';
                    $parameter = array('OBJECT' => Translation :: get('Role'));
                }
                elseif (count($ids) > $failures)
                {
                    $message = 'SomeObjectsNotDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('Roles'));
                }
                else
                {
                    $message = 'ObjectsNotDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('Roles'));
                }
            }
            else
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectDeleted';
                    $parameter = array('OBJECT' => Translation :: get('Role'));
                }
                else
                {
                    $message = 'ObjectsDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('Roles'));
                }
            }
            
            $this->redirect(Translation :: get($message, $parameter, Utilities :: COMMON_LIBRARIES), ($failures ? true : false), array(
                    Manager :: PARAM_ACTION => Manager :: ACTION_BROWSE));
        }
        else
        {
            $this->display_error_page(htmlentities(Translation :: get('NoObjectSelected', array(
                    'OBJECT' => Translation :: get('Role')), Utilities :: COMMON_LIBRARIES)));
        }
    }
}
?>