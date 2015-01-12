<?php
namespace Chamilo\Application\Discovery\Rights;

class TrainingBasedContext
{

    private $faculty_id;

    private $training_id;

    public function __construct($faculty_id, $training_id)
    {
        $this->faculty_id = $faculty_id;
        $this->training_id = $training_id;
    }

    /**
     *
     * @return field_type
     */
    public function get_faculty_id()
    {
        return $this->faculty_id;
    }

    /**
     *
     * @param field_type $faculty_id
     */
    public function set_faculty_id($faculty_id)
    {
        $this->faculty_id = $faculty_id;
    }

    /**
     *
     * @return field_type
     */
    public function get_training_id()
    {
        return $this->training_id;
    }

    /**
     *
     * @param field_type $training_id
     */
    public function set_training_id($training_id)
    {
        $this->training_id = $training_id;
    }
}