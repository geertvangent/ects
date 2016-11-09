<?php
namespace Ehb\Application\Discovery\Module\TrainingInfo;

class Parameters extends \Ehb\Application\Discovery\Parameters
{

    public function __construct($training_id)
    {
        $this->set_training_id($training_id);
    }

    public function set_training_id($training_id)
    {
        $this->set_parameter(Module::PARAM_TRAINING_ID, $training_id);
    }

    public function get_training_id()
    {
        return $this->get_parameter(Module::PARAM_TRAINING_ID);
    }
}
