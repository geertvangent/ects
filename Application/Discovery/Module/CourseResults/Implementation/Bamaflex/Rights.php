<?php
namespace Ehb\Application\Discovery\Module\CourseResults\Implementation\Bamaflex;

use Ehb\Application\Discovery\Module\CourseResults\DataManager;
use Ehb\Application\Discovery\Rights\TrainingBasedContext;
use Ehb\Application\Discovery\Rights\TrainingBasedRights;

class Rights extends TrainingBasedRights
{

    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $parameter = new \Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Parameters();
        $parameter->set_programme_id($parameters->get_programme_id());
        $parameter->set_source($parameters->get_source());
        
        $module_instance = \Ehb\Application\Discovery\Instance\Storage\DataManager::retrieve_by_id(
            \Ehb\Application\Discovery\Instance\Storage\DataClass\Instance::class_name(), 
            (int) $module_instance_id);
        
        $course = DataManager::getInstance($module_instance)->retrieve_course($parameter);
        
        return new TrainingBasedContext($course->get_faculty_id(), $course->get_training_id());
    }
}
