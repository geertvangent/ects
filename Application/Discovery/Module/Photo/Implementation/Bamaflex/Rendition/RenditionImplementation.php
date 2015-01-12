<?php
namespace Chamilo\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\RenditionImplementation
{

    public function get_student_years()
    {
        return $this->get_module()->get_student_years();
    }
}
