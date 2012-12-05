<?php
namespace application\discovery\module\cas;

class Parameters extends \application\discovery\Parameters
{
    const MODE_GENERAL = 1;
    const MODE_USER = 2;

    function __construct($user_id, $mode = self :: MODE_USER)
    {
        $this->set_user_id($user_id);
        $this->set_mode($mode);
    }

    function get_user_id()
    {
        return $this->get_parameter(Module :: PARAM_USER_ID);
    }

    function set_user_id($user_id)
    {
        $this->set_parameter(Module :: PARAM_USER_ID, $user_id);
    }

    function get_mode()
    {
        return $this->get_parameter(Module :: PARAM_MODE);
    }

    function set_mode($mode)
    {
        $this->set_parameter(Module :: PARAM_MODE, $mode);
    }
}
?>