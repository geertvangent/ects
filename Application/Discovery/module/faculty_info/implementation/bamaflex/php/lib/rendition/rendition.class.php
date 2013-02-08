<?php
namespace application\discovery\module\faculty_info\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_faculty()
    {
        return $this->get_module()->get_faculty();
    }

    function get_trainings_data($parameters)
    {
        return $this->get_module()->get_trainings_data($parameters);
    }
}
