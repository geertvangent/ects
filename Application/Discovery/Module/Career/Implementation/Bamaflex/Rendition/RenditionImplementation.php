<?php
namespace Ehb\Application\Discovery\Module\Career\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
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
