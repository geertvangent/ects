<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

class Parameters extends \application\discovery\module\training_info\Parameters
{

    function __construct($training_id, $source)
    {
        parent :: __construct($training_id);
        $this->set_source($source);
    }

    function set_source($source)
    {
        $this->set_parameter(Module :: PARAM_SOURCE, $source);
    }

    function get_source()
    {
        return $this->get_parameter(Module :: PARAM_SOURCE);
    }
}
?>