<?php
namespace Ehb\Application\Discovery\Instance\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Discovery\Instance\Manager;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;
use Ehb\Application\Discovery\Instance\Storage\DataManager;

class DeleterComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }

        $ids = Request :: get(Manager :: PARAM_MODULE_ID);
        $failures = 0;

        if (! empty($ids))
        {
            if (! is_array($ids))
            {
                $ids = array($ids);
            }

            foreach ($ids as $id)
            {
                $instance = DataManager :: retrieve_by_id(Instance :: class_name(), (int) $id);

                if (! $instance->delete())
                {
                    $failures ++;
                }
            }

            if ($failures)
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectNotDeleted';
                    $parameter = array('OBJECT' => Translation :: get('Instance'));
                }
                else
                {
                    $message = 'ObjectsNotDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('VideosConferencing'));
                }
            }
            else
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectDeleted';
                    $parameter = array('OBJECT' => Translation :: get('Instance'));
                }
                else
                {
                    $message = 'ObjectsDeleted';
                    $parameter = array('OBJECTS' => Translation :: get('VideosConferencing'));
                }
            }

            $this->redirect(
                Translation :: get($message, $parameter, Utilities :: COMMON_LIBRARIES),
                ($failures ? true : false),
                array(self :: PARAM_ACTION => self :: ACTION_BROWSE_INSTANCES));
        }
        else
        {
            return $this->display_error_page(
                htmlentities(
                    Translation :: get(
                        'NoObjectSelected',
                        array('OBJECT' => Translation :: get('Instance')),
                        Utilities :: COMMON_LIBRARIES)));
        }
    }
}
