<?php
namespace Application\Discovery\module\enrollment\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_enrollments()
    {
        return $this->get_module()->get_enrollments();
    }
}
