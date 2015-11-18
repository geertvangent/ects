<?php
namespace Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_course()
    {
        return $this->get_module()->get_course();
    }
}
