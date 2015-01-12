<?php
namespace Chamilo\Application\Discovery\Module\CourseResults;

class Parameters extends \Chamilo\Application\Discovery\Parameters
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
