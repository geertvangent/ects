<?php
namespace Chamilo\Application\Discovery\Module\Photo;

class Parameters extends \Chamilo\Application\Discovery\Parameters
{

    public function __construct($faculty_id = null, $training_id = null, $programme_id = null, $type = null)
    {
        $this->set_faculty_id($faculty_id);
        $this->set_training_id($training_id);
        $this->set_programme_id($programme_id);
        $this->set_type($type);
    }

    public function get_faculty_id()
    {
        return $this->get_parameter(Module :: PARAM_FACULTY_ID);
    }

    public function set_faculty_id($faculty_id)
    {
        $this->set_parameter(Module :: PARAM_FACULTY_ID, $faculty_id);
    }

    public function get_training_id()
    {
        return $this->get_parameter(Module :: PARAM_TRAINING_ID);
    }

    public function set_training_id($training_id)
    {
        $this->set_parameter(Module :: PARAM_TRAINING_ID, $training_id);
    }

    public function get_programme_id()
    {
        return $this->get_parameter(Module :: PARAM_PROGRAMME_ID);
    }

    public function set_programme_id($programme_id)
    {
        $this->set_parameter(Module :: PARAM_PROGRAMME_ID, $programme_id);
    }

    public function get_type()
    {
        return $this->get_parameter(Module :: PARAM_TYPE);
    }

    public function set_type($type)
    {
        $this->set_parameter(Module :: PARAM_TYPE, $type);
    }
}
