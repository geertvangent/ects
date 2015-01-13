<?php
namespace Chamilo\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_enrollments()
    {
        return $this->get_module()->get_enrollments();
    }
}
