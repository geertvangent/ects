<?php
namespace Application\Discovery\module\profile\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_profile()
    {
        return $this->get_module()->get_profile();
    }
}
