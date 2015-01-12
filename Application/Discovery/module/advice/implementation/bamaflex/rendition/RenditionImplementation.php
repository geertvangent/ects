<?php
namespace Application\Discovery\module\advice\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function has_advices($enrollment)
    {
        return $this->get_module()->has_advices($enrollment);
    }

    public function get_advices_data($enrollment)
    {
        return $this->get_module()->get_advices_data($enrollment);
    }
}
