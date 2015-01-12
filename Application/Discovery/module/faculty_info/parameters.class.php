<?php
namespace application\discovery\module\faculty_info;

class Parameters extends \application\discovery\Parameters
{

    public function __construct($faculty_id)
    {
        $this->set_faculty_id($faculty_id);
    }

    public function set_faculty_id($faculty_id)
    {
        $this->set_parameter(Module :: PARAM_FACULTY_ID, $faculty_id);
    }

    public function get_faculty_id()
    {
        return $this->get_parameter(Module :: PARAM_FACULTY_ID);
    }
}
