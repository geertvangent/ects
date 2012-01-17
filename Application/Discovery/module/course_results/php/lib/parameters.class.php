<?php
namespace application\discovery\module\course_results;

class Parameters extends \application\discovery\Parameters
{

    function __construct($programme_id)
    {
        $this->set_programme_id($programme_id);
    }

    function get_programme_id()
    {
        return $this->get_parameter(Module :: PARAM_PROGRAMME_ID);
    }

    function set_programme_id($programme_id)
    {
        $this->set_parameter(Module :: PARAM_PROGRAMME_ID, $programme_id);
    }
}
?>