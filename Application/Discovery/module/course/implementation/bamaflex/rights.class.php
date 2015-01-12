<?php
namespace Application\Discovery\module\course\implementation\bamaflex;

use application\discovery\module\course\DataManager;
use application\discovery\TrainingBasedRights;
use application\discovery\TrainingBasedContext;

class Rights extends TrainingBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $module_instance = \application\discovery\instance\DataManager :: retrieve_by_id(
            \application\discovery\instance\Instance :: class_name(), 
            (int) $module_instance_id);
        $course = DataManager :: get_instance($module_instance)->retrieve_course($parameters);
        
        return new TrainingBasedContext($course->get_faculty_id(), $course->get_training_id());
    }
}