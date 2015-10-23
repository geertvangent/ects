<?php
namespace Ehb\Application\Discovery\Module\Course;

class Parameters extends \Ehb\Application\Discovery\Parameters
{

    public function __construct($programme_id)
    {
        $this->set_programme_id($programme_id);
    }

    public function get_programme_id()
    {
        return $this->get_parameter(Module :: PARAM_PROGRAMME_ID);
    }

    public function set_programme_id($programme_id)
    {
        $this->set_parameter(Module :: PARAM_PROGRAMME_ID, $programme_id);
    }
}
