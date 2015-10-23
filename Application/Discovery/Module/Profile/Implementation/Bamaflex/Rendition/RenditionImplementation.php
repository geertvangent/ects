<?php
namespace Ehb\Application\Discovery\Module\Profile\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_profile()
    {
        return $this->get_module()->get_profile();
    }
}
