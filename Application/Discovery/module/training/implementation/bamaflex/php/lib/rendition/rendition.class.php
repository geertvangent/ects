<?php
namespace application\discovery\module\training\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_years()
    {
        return $this->get_module()->get_years();
    }

    function get_trainings_data($year)
    {
        return $this->get_module()->get_trainings_data($year);
    }
}
