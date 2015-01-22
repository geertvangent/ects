<?php
namespace Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex;

use Ehb\Application\Discovery\Module\TrainingResults\DataManager;
use Ehb\Application\Discovery\RIghts\TrainingBasedContext;
use Ehb\Application\Discovery\Rights\TrainingBasedRights;

class Rights extends TrainingBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $parameter = new \Ehb\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters();
        $parameter->set_training_id($parameters->get_training_id());
        $parameter->set_source($parameters->get_source());
        
        $module_instance = \Ehb\Application\Discovery\Instance\DataManager :: retrieve_by_id(
            \Ehb\Application\Discovery\Instance\DataClass\Instance :: class_name(), 
            (int) $module_instance_id);
        $training = DataManager :: get_instance($module_instance)->retrieve_training($parameter);
        
        return new TrainingBasedContext($training->get_faculty_id(), $training->get_id());
    }
}
