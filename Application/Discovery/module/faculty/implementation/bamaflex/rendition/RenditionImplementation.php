<?php
namespace Chamilo\Application\Discovery\Module\Faculty\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\RenditionImplementation
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
