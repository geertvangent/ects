<?php
namespace application\discovery\module\training\implementation\bamaflex;

class Parameters extends \application\discovery\module\training\Parameters
{

    function __construct($faculty_id, $source)
    {
        $this->set_source($source);
        $this->set_faculty_id($faculty_id);
    }

    function set_source($source)
    {
        $this->set_parameter(Module :: PARAM_SOURCE, $source);
    }

    function get_source()
    {
        return $this->get_parameter(Module :: PARAM_SOURCE);
    }

    function get_faculty_id()
    {
        return $this->get_parameter(Module :: PARAM_FACULTY_ID);
    }

    function set_faculty_id($faculty_id)
    {
        $this->set_parameter(Module :: PARAM_FACULTY_ID, $faculty_id);
    }
}
?>