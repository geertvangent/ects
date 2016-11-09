<?php
namespace Ehb\Application\Discovery\Module\Training;

class Parameters extends \Ehb\Application\Discovery\Parameters
{

    public function __construct($year)
    {
        $this->set_year($year);
    }

    public function set_year($year)
    {
        $this->set_parameter(Module::PARAM_YEAR, $year);
    }

    public function get_year()
    {
        return $this->get_parameter(Module::PARAM_YEAR);
    }
}
