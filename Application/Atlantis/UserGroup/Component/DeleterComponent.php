<?php
namespace Chamilo\Application\Atlantis\UserGroup\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Application\Atlantis\UserGroup\Manager;
use Chamilo\Application\Atlantis\UserGroup\Storage\DataManager;

class DeleterComponent extends Manager
{

    public function run()
    {
        $ids = Request :: get(self :: PARAM_APPLICATION_ID);
        $failures = 0;

        if (! empty($ids))
        {
            if (! is_array($ids))
            {
                $ids = array($ids);
            }

            foreach ($ids as $id)
            {
                $application = DataManager :: retrieve(
                    \Chamilo\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(),
                    (int) $id);

                if (! $this->get_user()->is_platform_admin())
                {
                    $failures ++;
                }
                else
                {
                    if (! $application->delete())
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
                    $parameter = array('OBJECT' => Translation :: get('Application'));
                }
                elseif (count($ids) > $failures)
                {
                    $message = 'SomeObjectsNotDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('Applications'));
                }
                else
                {
                    $message = 'ObjectsNotDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('Applications'));
                }
            }
            else
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectDeleted';
                    $parameter = array('OBJECT' => Translation :: get('Application'));
                }
                else
                {
                    $message = 'ObjectsDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('Applications'));
                }
            }

            $this->redirect(
                Translation :: get($message, $parameter, Utilities :: COMMON_LIBRARIES),
                ($failures ? true : false),
                array(Manager :: PARAM_ACTION => Manager :: ACTION_BROWSE));
        }
        else
        {
            $this->display_error_page(
                htmlentities(
                    Translation :: get(
                        'NoObjectSelected',
                        array('OBJECT' => Translation :: get('Application')),
                        Utilities :: COMMON_LIBRARIES)));
        }
    }
}
