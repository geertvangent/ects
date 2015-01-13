<?php
namespace Chamilo\Application\Discovery\Module\Course\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_course()
    {
        return $this->get_module()->get_course();
    }
}
