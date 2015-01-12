<?php
namespace Application\Discovery\module\group_user\implementation\bamaflex;

class Parameters extends \application\discovery\module\group_user\Parameters
{

    public function __construct($group_class_id, $source, $type)
    {
        parent :: __construct($group_class_id);
        $this->set_source($source);
        $this->set_type($type);
    }

    public function set_source($source)
    {
        $this->set_parameter(Module :: PARAM_SOURCE, $source);
    }

    public function get_source()
    {
        return $this->get_parameter(Module :: PARAM_SOURCE);
    }

    public function set_type($type)
    {
        $this->set_parameter(Module :: PARAM_TYPE, $type);
    }

    public function get_type()
    {
        return $this->get_parameter(Module :: PARAM_TYPE);
    }
}
