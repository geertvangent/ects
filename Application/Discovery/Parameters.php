<?php
namespace Chamilo\Application\Discovery;

class Parameters
{

    private $parameters = array();

    public function get_parameter($key)
    {
        return $this->parameters[$key];
    }

    public function set_parameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    public function get_parameters()
    {
        return $this->parameters;
    }
}
