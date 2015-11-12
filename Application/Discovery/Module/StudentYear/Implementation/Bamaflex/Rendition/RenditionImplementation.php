<?php
namespace Ehb\Application\Discovery\Module\StudentYear\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_student_years()
    {
        return $this->get_module()->get_student_years();
    }
}
