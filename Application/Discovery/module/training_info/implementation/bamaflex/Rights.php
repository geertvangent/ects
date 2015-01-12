<?php
namespace Application\Discovery\module\training_info\implementation\bamaflex;

use application\discovery\module\training_info\DataManager;
use application\discovery\TrainingBasedRights;
use application\discovery\TrainingBasedContext;

class Rights extends TrainingBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $parameter = new \application\discovery\module\training_info\implementation\bamaflex\Parameters();
        $parameter->set_training_id($parameters->get_training_id());
        $parameter->set_source($parameters->get_source());
        
        $module_instance = \application\discovery\instance\DataManager :: retrieve_by_id(
            \application\discovery\instance\Instance :: class_name(), 
            (int) $module_instance_id);
        $training = DataManager :: get_instance($module_instance)->retrieve_training($parameter);
        
        return new TrainingBasedContext($training->get_faculty_id(), $training->get_id());
    }
}