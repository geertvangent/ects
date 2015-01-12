<?php
namespace Application\Discovery\module\student_year;

class Parameters extends \application\discovery\Parameters
{

    public function __construct($user_id)
    {
        $this->set_user_id($user_id);
    }

    public function get_user_id()
    {
        return $this->get_parameter(Module :: PARAM_USER_ID);
    }

    public function set_user_id($user_id)
    {
        $this->set_parameter(Module :: PARAM_USER_ID, $user_id);
    }
}
