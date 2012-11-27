<?php
namespace application\discovery\module\faculty;

class Parameters extends \application\discovery\Parameters
{

    function __construct($year)
    {
        $this->set_year($year);
    }

    function set_year($year)
    {
        $this->set_parameter(Faculty :: PROPERTY_YEAR, $year);
    }

    function get_year()
    {
        return $this->get_parameter(Faculty :: PROPERTY_YEAR);
    }
}
?>