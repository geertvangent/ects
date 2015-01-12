<?php
namespace Chamilo\Application\Discovery\Module\Course\Implementation\Bamaflex;

use Chamilo\Application\Discovery\Module\Course\DataManager;
use Chamilo\Application\Discovery\Rights\TrainingBasedRights;
use Chamilo\Application\Discovery\TrainingBasedContext;

class Rights extends TrainingBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $module_instance = \Chamilo\Application\Discovery\Instance\DataManager :: retrieve_by_id(
            \Chamilo\Application\Discovery\Instance\DataClass\Instance :: class_name(), 
            (int) $module_instance_id);
        $course = DataManager :: get_instance($module_instance)->retrieve_course($parameters);
        
        return new TrainingBasedContext($course->get_faculty_id(), $course->get_training_id());
    }
}