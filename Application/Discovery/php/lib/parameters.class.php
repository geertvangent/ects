<?php
namespace application\discovery;

class Parameters
{

    private $parameters = array();

    function get_parameter($key)
    {
        return $this->parameters[$key];
    }

    function set_parameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    function get_parameters()
    {
        return $this->parameters;
    }
}
