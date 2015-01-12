<?php
namespace Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex;

class Parameters extends \Chamilo\Application\Discovery\Module\TrainingResults\Parameters
{

    public function __construct($training_id, $source)
    {
        parent :: __construct($training_id);
        $this->set_source($source);
    }

    public function set_source($source)
    {
        $this->set_parameter(Module :: PARAM_SOURCE, $source);
    }

    public function get_source()
    {
        return $this->get_parameter(Module :: PARAM_SOURCE);
    }
}
