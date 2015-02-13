<?php
namespace Ehb\Application\Discovery\Module\Enrollment\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_enrollments()
    {
        return $this->get_module()->get_enrollments();
    }
}
