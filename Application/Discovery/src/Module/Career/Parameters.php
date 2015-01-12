<?php
namespace Chamilo\Application\Discovery\Module\Career;

class Parameters extends \Chamilo\Application\Discovery\Parameters
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
