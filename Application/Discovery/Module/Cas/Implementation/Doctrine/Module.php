<?php
namespace Ehb\Application\Discovery\Module\Cas\Implementation\Doctrine;

use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;

//
class Module extends \Ehb\Application\Discovery\Module\Cas\Module
{

    private $action_statistics;

    public function get_action_statistics($action)
    {
        if (! isset($this->action_statistics))
        {
            $path = Path::getInstance()->getStoragePath() .
                 ClassnameUtilities::getInstance()->namespaceToPath(__NAMESPACE__) . '/cas_action_statistics/' .
                 md5(serialize($this->get_module_parameters()));
            
            if (! file_exists($path))
            {
                $this->action_statistics = array();
                
                foreach ($this->get_cas_statistics() as $cas_statistic)
                {
                    if ($cas_statistic->get_application_id())
                    {
                        $this->action_statistics[$cas_statistic->get_action_id()][$cas_statistic->get_application_id()][] = $cas_statistic;
                    }
                    else
                    {
                        $this->action_statistics[$cas_statistic->get_action_id()][0][] = $cas_statistic;
                    }
                }
                Filesystem::write_to_file($path, serialize($this->action_statistics));
            }
            else
            {
                $this->action_statistics = unserialize(file_get_contents($path));
            }
        }
        
        return $this->action_statistics[$action->get_id()];
    }
}
