<?php
namespace application\discovery\module\teaching_assignment;

class Parameters extends \application\discovery\Parameters
{

    function __construct($user_id, $year)
    {
        $this->set_user_id($user_id);
        $this->set_year($year);
    }

    function get_user_id()
    {
        return $this->get_parameter(Module :: PARAM_USER_ID);
    }

    function set_user_id($user_id)
    {
        $this->set_parameter(Module :: PARAM_USER_ID, $user_id);
    }

    function set_year($year)
    {
        $this->set_parameter(Module :: PARAM_YEAR, $year);
    }

    function get_year()
    {
        return $this->get_parameter(Module :: PARAM_YEAR);
    }
}
