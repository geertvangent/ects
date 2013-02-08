<?php
namespace application\discovery\module\training_results;

class Parameters extends \application\discovery\Parameters
{

    function __construct($training_id)
    {
        $this->set_training_id($training_id);
    }

    function get_training_id()
    {
        return $this->get_parameter(Module :: PARAM_TRAINING_ID);
    }

    function set_training_id($training_id)
    {
        $this->set_parameter(Module :: PARAM_TRAINING_ID, $training_id);
    }
}
