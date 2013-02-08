<?php
namespace application\discovery\module\faculty_info;

class Parameters extends \application\discovery\Parameters
{

    function __construct($faculty_id)
    {
        $this->set_faculty_id($faculty_id);
    }

    function set_faculty_id($faculty_id)
    {
        $this->set_parameter(Module :: PARAM_FACULTY_ID, $faculty_id);
    }

    function get_faculty_id()
    {
        return $this->get_parameter(Module :: PARAM_FACULTY_ID);
    }
}
