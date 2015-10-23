<?php
namespace Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_student_years()
    {
        return $this->get_module()->get_student_years();
    }
}
