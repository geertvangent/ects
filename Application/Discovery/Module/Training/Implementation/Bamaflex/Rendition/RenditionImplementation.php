<?php
namespace Chamilo\Application\Discovery\Module\Training\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\Rendition\RenditionImplementation
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
