<?php
namespace application\discovery\module\course_results\implementation\bamaflex;

class Parameters extends \application\discovery\module\course_results\Parameters
{

    function __construct($programme_id, $source)
    {
        parent :: __construct($programme_id);
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
