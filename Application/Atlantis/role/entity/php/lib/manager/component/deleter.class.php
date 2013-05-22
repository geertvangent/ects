<?php
namespace application\atlantis\role\entity;

use common\libraries\Request;
use common\libraries\Utilities;
use common\libraries\Translation;

class DeleterComponent extends Manager
{

    public function run()
    {
        $ids = Request :: get(self :: PARAM_ROLE_ENTITY_ID);
        $context_id = Request :: get(\application\atlantis\context\Manager :: PARAM_CONTEXT_ID);
        $role_id = Request :: get(\application\atlantis\role\Manager :: PARAM_ROLE_ID);

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

                if (! \application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
                {
                    $failures ++;
                }
                else
                {
                    if (! $role_entity->delete())
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
                    \application\atlantis\role\Manager :: PARAM_ROLE_ID => $role_id,
                    \application\atlantis\context\Manager :: PARAM_CONTEXT_ID => $context_id));
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
