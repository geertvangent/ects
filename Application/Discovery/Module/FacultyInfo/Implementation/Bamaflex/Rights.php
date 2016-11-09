<?php
namespace Ehb\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex;

use Ehb\Application\Discovery\Module\FacultyInfo\DataManager;
use Ehb\Application\Discovery\Rights\FacultyBasedRights;

class Rights extends FacultyBasedRights
{

    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $module_instance = \Ehb\Application\Discovery\Instance\Storage\DataManager::retrieve_by_id(
            \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance::class_name(), 
            (int) $module_instance_id);
        $faculty = DataManager::getInstance($module_instance)->retrieve_faculty($parameters);
        
        return $faculty->get_id();
    }
}
