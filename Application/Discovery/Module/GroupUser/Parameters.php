<?php
namespace Ehb\Application\Discovery\Module\GroupUser;

class Parameters extends \Ehb\Application\Discovery\Parameters
{

    public function __construct($group_class_id)
    {
        $this->set_group_class_id($group_class_id);
    }

    public function get_group_class_id()
    {
        return $this->get_parameter(Module::PARAM_GROUP_CLASS_ID);
    }

    public function set_group_class_id($group_class_id)
    {
        $this->set_parameter(Module::PARAM_GROUP_CLASS_ID, $group_class_id);
    }
}
