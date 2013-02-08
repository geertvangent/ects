<?php
namespace application\discovery\module\group_user;

class Parameters extends \application\discovery\Parameters
{

    function __construct($group_class_id)
    {
        $this->set_group_class_id($group_class_id);
    }

    function get_group_class_id()
    {
        return $this->get_parameter(Module :: PARAM_GROUP_CLASS_ID);
    }

    function set_group_class_id($group_class_id)
    {
        $this->set_parameter(Module :: PARAM_GROUP_CLASS_ID, $group_class_id);
    }
}
