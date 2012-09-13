<?php
namespace application\discovery\module\faculty_info\implementation\bamaflex;

class Parameters extends \application\discovery\module\faculty_info\Parameters
{

    function __construct($faculty_id, $source)
    {
        parent :: __construct($faculty_id);
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