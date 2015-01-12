<?php
namespace Application\Discovery\module\faculty\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_years()
    {
        return $this->get_module()->get_years();
    }

    public function get_faculties_data($year)
    {
        return $this->get_module()->get_faculties_data($year);
    }
}
