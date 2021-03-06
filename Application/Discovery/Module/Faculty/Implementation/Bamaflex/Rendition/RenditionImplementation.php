<?php
namespace Ehb\Application\Discovery\Module\Faculty\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
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
