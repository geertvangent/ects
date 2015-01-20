<?php
namespace Chamilo\Application\Discovery\Module\FacultyInfo\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_faculty()
    {
        return $this->get_module()->get_faculty();
    }

    public function get_trainings_data($parameters)
    {
        return $this->get_module()->get_trainings_data($parameters);
    }
}
