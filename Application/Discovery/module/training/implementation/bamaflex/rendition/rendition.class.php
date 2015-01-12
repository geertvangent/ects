<?php
namespace Application\Discovery\module\training\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_years()
    {
        return $this->get_module()->get_years();
    }

    public function get_trainings_data($year)
    {
        return $this->get_module()->get_trainings_data($year);
    }
}
