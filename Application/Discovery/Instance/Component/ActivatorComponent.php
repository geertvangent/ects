<?php
namespace Ehb\Application\Discovery\Instance\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Discovery\Instance\Manager;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;
use Ehb\Application\Discovery\Instance\Storage\DataManager;

class ActivatorComponent extends Manager
{

    public function run()
    {
        if (! $this->get_user()->is_platform_admin())
        {
            $this->not_allowed();
        }
        
        $ids = Request::get(Manager::PARAM_MODULE_ID);
        $failures = 0;
        
        if (! empty($ids))
        {
            if (! is_array($ids))
            {
                $ids = array($ids);
            }
            
            foreach ($ids as $id)
            {
                $instance = DataManager::retrieve_by_id(Instance::class_name(), (int) $id);
                $instance->activate();
                
                if (! $instance->update())
                {
                    $failures ++;
                }
            }
            
            if ($failures)
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectNotActivated';
                    $parameter = array('OBJECT' => Translation::get('Instance'));
                }
                else
                {
                    $message = 'ObjectsNotActivated';
                    $parameter = array('OBJECTS' => Translation::get('VideosConferencing'));
                }
            }
            else
            {
                if (count($ids) == 1)
                {
                    $message = 'ObjectActivated';
                    $parameter = array('OBJECT' => Translation::get('Instance'));
                }
                else
                {
                    $message = 'ObjectsActivated';
                    $parameter = array('OBJECTS' => Translation::get('VideosConferencing'));
                }
            }
            
            $this->redirect(
                Translation::get($message, $parameter, Utilities::COMMON_LIBRARIES), 
                ($failures ? true : false), 
                array(
                    self::PARAM_ACTION => self::ACTION_BROWSE_INSTANCES, 
                    self::PARAM_CONTENT_TYPE => $instance->get_content_type()));
        }
        else
        {
            return $this->display_error_page(
                htmlentities(
                    Translation::get(
                        'NoObjectSelected', 
                        array('OBJECT' => Translation::get('ExternalRepository')), 
                        Utilities::COMMON_LIBRARIES)));
        }
    }
}
