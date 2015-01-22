<?php
namespace Chamilo\Application\Discovery\Module\GroupUser\Implementation\Bamaflex;

use Chamilo\Application\Discovery\Module\GroupUser\DataManager;
use Chamilo\Application\Discovery\Rights\TrainingBasedContext;
use Chamilo\Application\Discovery\Rights\TrainingBasedRights;

class Rights extends TrainingBasedRights
{
    /*
     * (non-PHPdoc) @see \application\discovery\TrainingBasedRights::get_context()
     */
    public function get_context($module_instance_id, $parameters)
    {
        $parameter = new \Chamilo\Application\Discovery\Module\GroupUser\Implementation\Bamaflex\Parameters();
        $parameter->set_group_class_id($parameters->get_group_class_id());
        $parameter->set_type($parameters->get_type());
        $parameter->set_source($parameters->get_source());
        
        $module_instance = \Chamilo\Application\Discovery\Instance\DataManager :: retrieve_by_id(
            \Chamilo\Application\Discovery\Instance\DataClass\Instance :: class_name(), 
            (int) $module_instance_id);
        $group = DataManager :: get_instance($module_instance)->retrieve_group($parameter);
        
        $parameter = new \Chamilo\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Parameters();
        $parameter->set_training_id($group->get_training_id());
        $parameter->set_source($group->get_source());
        
        $training = \Chamilo\Application\Discovery\Module\Group\DataManager :: get_instance($module_instance)->retrieve_training(
            $parameter);
        
        return new TrainingBasedContext($training->get_faculty_id(), $training->get_id());
    }
}