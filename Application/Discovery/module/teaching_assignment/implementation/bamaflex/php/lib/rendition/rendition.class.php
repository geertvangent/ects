<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_teaching_assignments_data($parameters)
    {
        return $this->get_module()->get_teaching_assignments_data($parameters);
    }

    function get_years()
    {
        return $this->get_module()->get_years();
    }
}
