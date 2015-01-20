<?php
namespace Chamilo\Application\Discovery\Module\Cas;

class Parameters extends \Chamilo\Application\Discovery\Parameters
{
    const MODE_GENERAL = 1;
    const MODE_USER = 2;

    public function __construct($user_id, $mode = self :: MODE_USER)
    {
        $this->set_user_id($user_id);
        $this->set_mode($mode);
    }

    public function get_user_id()
    {
        return $this->get_parameter(Module :: PARAM_USER_ID);
    }

    public function set_user_id($user_id)
    {
        $this->set_parameter(Module :: PARAM_USER_ID, $user_id);
    }

    public function get_mode()
    {
        return $this->get_parameter(Module :: PARAM_MODE);
    }

    public function set_mode($mode)
    {
        $this->set_parameter(Module :: PARAM_MODE, $mode);
    }
}
