<?php
namespace application\discovery\module\group_user\implementation\bamaflex;

use application\discovery\module\group_user\DataManager;
use application\discovery\TrainingBasedRights;
use application\discovery\TrainingBasedContext;

class Rights extends TrainingBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $parameter = new \application\discovery\module\group_user\implementation\bamaflex\Parameters();
        $parameter->set_group_class_id($parameters->get_group_class_id());
        $parameter->set_type($parameters->get_type());
        $parameter->set_source($parameters->get_source());
        
        $module_instance = \application\discovery\instance\DataManager :: retrieve_by_id(
            \application\discovery\instance\Instance :: class_name(), 
            (int) $module_instance_id);
        $group = DataManager :: get_instance($module_instance)->retrieve_group($parameter);
        
        $parameter = new \application\discovery\module\training_info\implementation\bamaflex\Parameters();
        $parameter->set_training_id($group->get_training_id());
        $parameter->set_source($group->get_source());
        
        $training = \application\discovery\module\group\DataManager :: get_instance($module_instance)->retrieve_training(
            $parameter);
        
        return new TrainingBasedContext($training->get_faculty_id(), $training->get_id());
    }
}