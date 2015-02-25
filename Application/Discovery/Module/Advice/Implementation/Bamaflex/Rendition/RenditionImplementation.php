<?php
namespace Ehb\Application\Discovery\Module\Advice\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
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
