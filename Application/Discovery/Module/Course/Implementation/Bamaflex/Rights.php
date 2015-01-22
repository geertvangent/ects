<?php
namespace Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex;

use Ehb\Application\Discovery\Module\Course\DataManager;
use Ehb\Application\Discovery\Rights\TrainingBasedContext;
use Ehb\Application\Discovery\Rights\TrainingBasedRights;

class Rights extends TrainingBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $module_instance = \Ehb\Application\Discovery\Instance\DataManager :: retrieve_by_id(
            \Ehb\Application\Discovery\Instance\DataClass\Instance :: class_name(), 
            (int) $module_instance_id);
        $course = DataManager :: get_instance($module_instance)->retrieve_course($parameters);
        
        return new TrainingBasedContext($course->get_faculty_id(), $course->get_training_id());
    }
}