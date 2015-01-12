<?php
namespace Application\Discovery\module\course\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_course()
    {
        return $this->get_module()->get_course();
    }
}
