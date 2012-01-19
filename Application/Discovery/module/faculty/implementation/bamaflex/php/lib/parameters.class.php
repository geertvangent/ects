<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

class Parameters extends \application\discovery\Parameters
{

    function __construct($faculty_id, $source)
    {
        $this->set_source($source);
        $this->set_faculty_id($faculty_id);
    }

    function set_source($source)
    {
        $this->set_parameter(Faculty :: PROPERTY_SOURCE, $source);
    }

    function get_source()
    {
        return $this->get_parameter(Faculty :: PROPERTY_SOURCE);
    }

    function get_faculty_id()
    {
        return $this->get_parameter(Faculty :: PROPERTY_ID);
    }

    function set_faculty_id($faculty_id)
    {
        $this->set_parameter(Faculty :: PROPERTY_ID, $faculty_id);
    }
}
?>