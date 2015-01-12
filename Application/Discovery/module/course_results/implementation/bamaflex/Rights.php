<?php
namespace Application\Discovery\module\course_results\implementation\bamaflex;

use application\discovery\module\course_results\DataManager;
use application\discovery\TrainingBasedRights;
use application\discovery\TrainingBasedContext;

class Rights extends TrainingBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $parameter = new \application\discovery\module\course\implementation\bamaflex\Parameters();
        $parameter->set_programme_id($parameters->get_programme_id());
        $parameter->set_source($parameters->get_source());
        
        $module_instance = \application\discovery\instance\DataManager :: retrieve_by_id(
            \application\discovery\instance\Instance :: class_name(), 
            (int) $module_instance_id);
        
        $course = DataManager :: get_instance($module_instance)->retrieve_course($parameter);
        
        return new TrainingBasedContext($course->get_faculty_id(), $course->get_training_id());
    }
}
