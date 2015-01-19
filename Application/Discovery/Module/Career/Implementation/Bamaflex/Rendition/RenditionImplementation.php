<?php
namespace Chamilo\Application\Discovery\Module\Career\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_courses()
    {
        return $this->get_module()->get_courses();
    }

    public function get_mark_moments()
    {
        return $this->get_module()->get_mark_moments();
    }
}
