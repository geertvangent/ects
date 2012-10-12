<?php
namespace application\discovery\module\employment;

class Parameters extends \application\discovery\Parameters
{

    function __construct($user_id)
    {
        $this->set_user_id($user_id);
    }

    function get_user_id()
    {
        return $this->get_parameter(Module :: PARAM_USER_ID);
    }

    function set_user_id($user_id)
    {
        $this->set_parameter(Module :: PARAM_USER_ID, $user_id);
    }
}
?>